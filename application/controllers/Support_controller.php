<?php defined('BASEPATH') or exit('No direct script access allowed');

class Support_controller extends Home_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('support_model');
    }

    /**
     * Help Center
     */
    public function help_center()
    {
        $data['title'] = trans("help_center");
        $data['description'] = $this->general_settings->application_name . " - " . trans("help_center");
        $data['keywords'] = $this->general_settings->application_name . "," . trans("help_center");
        $data['support_categories'] = $this->support_model->get_categories_by_lang($this->selected_lang->id);

        $this->load->view('partials/_header', $data);
        $this->load->view('support/index', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Category
     */
    public function category($slug)
    {
        $data['category'] = $this->support_model->get_category_by_slug($slug);
        if (empty($data['category'])) {
            redirect(generate_url('help_center'));
            exit();
        }
        $data['articles'] = $this->support_model->get_contents_by_category($data['category']->id);
        if (empty($data['articles'])) {
            redirect(generate_url('help_center'));
            exit();
        }
        $data['article'] = $this->support_model->get_first_content_by_category($data['category']->id);

        $data['title'] = $data['category']->name . " - " . trans("help_center");
        $data['description'] = $this->general_settings->application_name . " - " . $data['title'];
        $data['keywords'] = $this->general_settings->application_name . "," . trans("help_center");

        $this->load->view('partials/_header', $data);
        $this->load->view('support/content', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Article
     */
    public function article($slug_category, $slug_article)
    {
        $data['category'] = $this->support_model->get_category_by_slug($slug_category);
        if (empty($data['category'])) {
            redirect(generate_url('help_center'));
            exit();
        }
        $data['articles'] = $this->support_model->get_contents_by_category($data['category']->id);
        $data['article'] = $this->support_model->get_content_by_slug($slug_article);

        if (empty($data['article'])) {
            redirect(generate_url('help_center'));
            exit();
        }

        $data['title'] = $data['category']->name . " - " . trans("help_center");
        $data['description'] = $this->general_settings->application_name . " - " . $data['title'];
        $data['keywords'] = $this->general_settings->application_name . "," . trans("help_center");

        $this->load->view('partials/_header', $data);
        $this->load->view('support/content', $data);
        $this->load->view('partials/_footer');
    }


    /**
     * Search
     */
    public function search()
    {
        get_method();
        $q = input_get('q');
        if (empty($q)) {
            redirect(generate_url('help_center'));
        }

        $data['title'] = trans("search") . " - " . html_escape($q) . " - " . trans("help_center");
        $data['description'] = $this->general_settings->application_name . " - " . $data['title'];
        $data['keywords'] = $this->general_settings->application_name . "," . trans("help_center");
        $data['q'] = $q;

        $data['num_rows'] = $this->support_model->get_content_search_count($this->selected_lang->id, $q);
        $pagination = $this->paginate(generate_url("help_center", "search"), $data['num_rows'], 15);
        $data['contents'] = $this->support_model->get_content_search_results($this->selected_lang->id, $q, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('support/search', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Tickets
     */
    public function tickets()
    {
        if (!$this->auth_check) {
            redirect(generate_url('help_center'));
            exit();
        }

        $data['title'] = trans("support_tickets") . " - " . trans("help_center");
        $data['description'] = $this->general_settings->application_name . " - " . $data['title'];
        $data['keywords'] = $this->general_settings->application_name . "," . trans("help_center");

        $data['tickets'] = $this->support_model->get_tickets_by_user($this->auth_user->id);

        $this->load->view('partials/_header', $data);
        $this->load->view('support/tickets', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Support
     */
    public function submit_request()
    {
        $data['title'] = trans("submit_a_request") . " - " . trans("help_center");
        $data['description'] = $this->general_settings->application_name . " - " . $data['title'];
        $data['keywords'] = $this->general_settings->application_name . "," . trans("help_center");
        $data['load_support_editor'] = true;

        $this->load->view('partials/_header', $data);
        $this->load->view('support/submit_request', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Submit a Request Post
     */
    public function submit_request_post()
    {
        post_method();
        if (!$this->auth_check) {
            $this->form_validation->set_rules('name', trans("name"), 'required|max_length[255]');
            $this->form_validation->set_rules('email', trans("email"), 'required|max_length[255]');
        }
        $this->form_validation->set_rules('subject', trans("subject"), 'required|max_length[500]');
        $this->form_validation->set_rules('message', trans("message"), 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->support_model->input_values_ticket());
            redirect($this->agent->referrer());
        } else {
            if (!$this->recaptcha_verify_request()) {
                $this->session->set_flashdata('form_data', $this->support_model->input_values_ticket());
                $this->session->set_flashdata('error', trans("msg_recaptcha"));
                redirect($this->agent->referrer());
            } else {
                $is_support_reply = false;
                if ($this->support_model->add_ticket($is_support_reply)) {
                    $msg = trans("msg_message_sent") . '&nbsp;<a href="' . generate_url('help_center', 'tickets') . '" style="color: #107ef4; border-bottom: 1px solid #107ef4;">' . trans('support_tickets') . '</a>';
                    if (!$this->auth_check) {
                        $msg = trans("msg_message_sent");
                    }
                    $this->session->set_flashdata('success', $msg);
                } else {
                    $this->session->set_flashdata('form_data', $this->support_model->input_values());
                    $this->session->set_flashdata('error', trans("msg_error"));
                }
            }
        }
        redirect($this->agent->referrer());
    }

    /**
     * Send Message Post
     */
    public function send_message_post()
    {
        $is_support_reply = false;
        $ticket_id = $this->input->post('ticket_id');
        if ($this->support_model->add_subticket($ticket_id, $is_support_reply)) {
            $this->session->set_flashdata('success', trans("msg_message_sent"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Ticket
     */
    public function ticket($id)
    {
        $data['title'] = trans("submit_a_request") . " - " . trans("help_center");
        $data['description'] = $this->general_settings->application_name . " - " . $data['title'];
        $data['keywords'] = $this->general_settings->application_name . "," . trans("help_center");
        $data['load_support_editor'] = true;

        $data['ticket'] = $this->support_model->get_ticket($id);
        $data['subtickets'] = $this->support_model->get_subtickets($id);

        if (empty($data['ticket'])) {
            redirect(lang_base_url());
            exit();
        }
        if ($data['ticket']->user_id != $this->auth_user->id) {
            redirect(lang_base_url());
            exit();
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('support/ticket', $data);
        $this->load->view('partials/_footer');
    }

    //close ticket
    public function close_ticket_post()
    {
        $id = $this->input->post('id');
        $this->support_model->close_ticket($id);
    }

    //upload attachment
    public function upload_support_attachment()
    {
        $ticket_type = $this->input->post('ticket_type');
        $this->file_model->upload_attachment($ticket_type);
        $this->print_support_attachments($ticket_type);
    }

    //print attachments
    public function print_support_attachments($ticket_type)
    {
        $html = "";
        if (!empty($this->session->userdata('ticket_attachments'))) {
            $filesSession = $this->session->userdata('ticket_attachments');
            foreach ($filesSession as $file) {
                if (!empty($file->uniqid) && !empty($file->name) && !empty($file->ticket_type) && $file->ticket_type == $ticket_type) {
                    $icon = '<i class="fa fa-times"></i>';
                    if ($file->ticket_type == 'client') {
                        $icon = '<i class="icon-times"></i>';
                    }
                    $html .= '<div class="item"><div class="item-inner">';
                    $html .= html_escape($file->name) . '<a href="javascript:void(0)" onclick="delete_support_attachment(\'' . $file->uniqid . '\')">' . $icon . '</a>';
                    $html .= '</div></div>';
                }
            }
        }
        $response = array(
            'result' => 1,
            'response' => $html
        );
        echo json_encode($response);
    }

    //delete attachment
    public function delete_support_attachment()
    {
        $id = $this->input->post('id', true);
        $ticket_type = $this->input->post('ticket_type', true);
        $this->file_model->delete_attachment($id);
        $this->print_support_attachments($ticket_type);
    }

    //download attachment
    public function download_attachment()
    {
        $name = $this->input->post('name');
        $path = $this->input->post('path');
        $this->load->helper('download');
        force_download($name, file_get_contents($path));
    }

}

