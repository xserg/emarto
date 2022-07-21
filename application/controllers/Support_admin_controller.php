<?php defined('BASEPATH') or exit('No direct script access allowed');

class Support_admin_controller extends Admin_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
        check_permission('help_center');
        $this->load->model('support_model');
    }

    /**
     * Knowledge Base
     */
    public function knowledge_base()
    {
        $data['title'] = trans("knowledge_base");
        $lang_id = input_get('lang');
        if (empty($lang_id) || empty($this->language_model->get_language($lang_id))) {
            redirect(admin_url() . 'knowledge-base?lang=' . $this->general_settings->site_lang);
            exit();
        }

        $data['contents'] = $this->support_model->get_contents_by_lang($lang_id);
        $data['categories'] = $this->support_model->get_categories_by_lang($lang_id);
        $data['lang_id'] = $lang_id;

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/support/knowledge_base', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Content
     */
    public function add_content()
    {
        $data['title'] = trans("add_content");
        $data['categories'] = $this->support_model->get_categories_by_lang($this->general_settings->site_lang);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/support/add_content', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Content Post
     */
    public function add_content_post()
    {
        //validate inputs
        $this->form_validation->set_rules('title', trans("title"), 'required|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->support_model->input_values());
            redirect(admin_url() . 'knowledge-base/add-content?lang=' . $lang_id);
            exit();
        } else {
            if ($this->support_model->add_content()) {
                $lang_id = $this->input->post('lang_id', true);
                $this->session->set_flashdata('success', trans("msg_added"));
            } else {
                $this->session->set_flashdata('form_data', $this->support_model->input_values());
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        }
        redirect($this->agent->referrer());
    }

    /**
     * Edit Content
     */
    public function edit_content($id)
    {
        $data['title'] = trans("edit_content");
        $data['content'] = $this->support_model->get_content($id);
        if (empty($data['content'])) {
            redirect(admin_url() . 'knowledge-base');
            exit();
        }
        $data['categories'] = $this->support_model->get_categories_by_lang($data['content']->lang_id);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/support/edit_content', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Edit Content Post
     */
    public function edit_content_post()
    {
        //validate inputs
        $this->form_validation->set_rules('title', trans("title"), 'required|max_length[500]');
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->support_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $id = $this->input->post('id', true);
            $lang_id = $this->input->post('lang_id', true);
            if ($this->support_model->edit_content($id)) {
                $this->session->set_flashdata('success', trans("msg_updated"));
                redirect(admin_url() . 'knowledge-base?lang=' . $lang_id);
            } else {
                $this->session->set_flashdata('form_data', $this->support_model->input_values());
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Delete Content Post
     */
    public function delete_content_post()
    {
        $id = $this->input->post('id', true);
        if ($this->support_model->delete_content($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        exit();
    }

    /**
     * Add Category
     */
    public function add_category()
    {
        $data['title'] = trans("add_category");

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/support/add_category', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Category Post
     */
    public function add_category_post()
    {
        //validate inputs
        $this->form_validation->set_rules('name', trans("name"), 'required|max_length[255]');
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
        } else {
            if ($this->support_model->add_category()) {
                $lang_id = $this->input->post('lang_id', true);
                $this->session->set_flashdata('success', trans("msg_added"));
                redirect(admin_url() . 'knowledge-base/add-category?lang=' . $lang_id);
                exit();
            } else {
                $this->session->set_flashdata('form_data', $this->support_model->input_values());
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        }
        redirect($this->agent->referrer());
    }

    /**
     * Edit Category
     */
    public function edit_category($id)
    {
        $data['title'] = trans("update_category");
        $data['category'] = $this->support_model->get_category($id);
        if (empty($data['category'])) {
            redirect(admin_url() . 'knowledge-base');
            exit();
        }
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/support/edit_category', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Edit Category Post
     */
    public function edit_category_post()
    {
        //validate inputs
        $this->form_validation->set_rules('name', trans("name"), 'required|max_length[255]');
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect($this->agent->referrer());
        } else {
            $id = $this->input->post('id', true);
            $lang_id = $this->input->post('lang_id', true);
            if ($this->support_model->edit_category($id)) {
                $this->session->set_flashdata('success', trans("msg_updated"));
                redirect(admin_url() . 'knowledge-base?lang=' . $lang_id);
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Delete Category Post
     */
    public function delete_category_post()
    {
        $id = $this->input->post('id', true);
        if ($this->support_model->delete_category($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        exit();
    }

    //get categories by language
    public function get_categories_by_lang()
    {
        $lang_id = $this->input->post('lang_id', true);
        if (!empty($lang_id)):
            $categories = $this->support_model->get_categories_by_lang($lang_id);
            foreach ($categories as $item) {
                echo '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        endif;
    }

    /**
     * Support Tickets
     */
    public function support_tickets()
    {
        $data['title'] = trans("support_tickets");
        $status = clean_number(input_get('status'));
        if ($status != 1 && $status != 2 && $status != 3) {
            $status = 1;
        }
        $data['status'] = $status;
        $data['num_rows'] = $this->support_model->get_tickets_count($status);
        $data['num_rows_open'] = $this->support_model->get_tickets_count(1);
        $data['num_rows_responded'] = $this->support_model->get_tickets_count(2);
        $data['num_rows_closed'] = $this->support_model->get_tickets_count(3);
        $pagination = $this->paginate(admin_url() . "/support-tickets", $data['num_rows']);
        $data['tickets'] = $this->support_model->get_tickets_paginated($status, $pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/support/tickets', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Support Ticket
     */
    public function support_ticket($id)
    {
        $data['ticket'] = $this->support_model->get_ticket($id);
        if (empty($data['ticket'])) {
            redirect(admin_url() . 'support-tickets');
            exit();
        }
        $data['title'] = trans("ticket");
        $data['subtickets'] = $this->support_model->get_subtickets($id);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/support/ticket', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Send Message Post
     */
    public function send_message_post()
    {
        $is_support_reply = true;
        $ticket_id = $this->input->post('ticket_id');
        if ($this->support_model->add_subticket($ticket_id, $is_support_reply)) {
            $this->session->set_flashdata('success', trans("msg_message_sent"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    //change ticket status
    public function change_ticket_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $this->support_model->change_ticket_status($id, $status);
    }

    /**
     * Delete Ticket Post
     */
    public function delete_ticket_post()
    {
        $id = $this->input->post('id', true);
        if ($this->support_model->delete_ticket($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        exit();
    }
}

