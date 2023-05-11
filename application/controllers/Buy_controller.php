<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Buy_controller extends Home_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * buy Requests
     */
    public function buy_requests()
    {
        $data['title'] = trans("buy_requests");
        $data['description'] = trans("buy_requests") . " - " . $this->app_name;
        $data['keywords'] = trans("buy_requests") . "," . $this->app_name;
        $data['parent_categories'] = $this->category_model->get_all_parent_categories();
        
        $num_rows = $this->buy_model->get_buy_requests_count($this->auth_user->id);
        $pagination = $this->paginate(generate_url("buy_requests"), $num_rows, 10);
        $data['refund_requests'] = $this->buy_model->get_buy_requests_paginated($this->auth_user->id, $pagination['per_page'], $pagination['offset']);
        
        //$data['user_orders'] = $this->order_model->get_orders_by_buyer_id($this->user_id);
        //$data['active_refund_request_ids'] = $this->order_model->get_buyer_active_refund_request_ids($this->user_id);
//echo '<pre>'; print_r($data);
        $this->load->view('partials/_header', $data);
        $this->load->view('buy/buy_requests', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Refund
     */
    public function refund($id)
    {
        $data['title'] = trans("buy_requests");
        $data['description'] = trans("buy_requests") . " - " . $this->app_name;
        $data['keywords'] = trans("buy request") . "," . $this->app_name;

        $data['product'] = $this->buy_model->get_buy_request($id);
        $data['product_images'] = $this->file_model->get_buy_images($id);
        
        $data['title'] = $data['product']->title;
        $data['description'] = $data['product']->description;
        $data['price'] = $data['product']->price;
        $data['currency'] = $data['product']->currency;
        $data["user"] = $this->auth_model->get_user_by_slug($data['product']->user_slug);
        //echo '<pre>'; print_r($data);
        //exit;
        
        /*
        if (empty($data['refund_request'])) {
            redirect(generate_url("refund_requests"));
            exit();
        }
        if (!is_admin() && $data['refund_request']->buyer_id != $this->auth_user->id) {
            redirect(generate_url("refund_requests"));
            exit();
        }
        $data['product'] = get_order_product($data['refund_request']->order_product_id);
        if (empty($data['product'])) {
            redirect(generate_url("refund_requests"));
            exit();
        }
        $data['messages'] = $this->order_model->get_refund_messages($id);
        */
        $this->load->view('partials/_header', $data);
        $this->load->view('buy/details', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Submit Refund Request
     */
    public function submit_buy_request()
    {
      if (!$this->auth_user->id) {
        redirect($this->agent->referrer());
          return;
      }
        
        $category_id  = $this->input->post('category_id', true);
        $description  = $this->input->post('description', true);
        $price  = $this->input->post('price', true);
        $currency = $this->input->post('currency');
        
        $buy_request_id = $this->buy_model->add_buy_request();
        
        $this->file_model->upload_buy_image($buy_request_id);
        
        if (!empty($order_product)) {
            $user = get_user($order_product->seller_id);
            $refund_id = $this->order_model->add_refund_request($order_product);
            
            if (!empty($this->general_settings->mail_username) && !empty($user) && !empty($refund_id)) {
                $email_data = array(
                    'email_type' => 'email_general',
                    'to' => $user->email,
                    'subject' => trans("refund_request"),
                    'email_content' => trans("msg_refund_request_email"),
                    'email_link' => generate_dash_url("refund_requests") . "/" . $refund_id,
                    'email_button_text' => trans("see_details")
                );
                $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
            }
        }
        
        redirect($this->agent->referrer());
    }

    /**
     * Add Refund Message
     */
    public function add_refund_message()
    {
        $id = $this->input->post('id', true);
        $request = $this->order_model->get_refund_request($id);
        if (!empty($request)) {
            $mail_user_id = null;
            $refund_url = generate_url("refund_requests") . "/" . $request->id;
            if ($request->buyer_id == $this->auth_user->id) {
                $this->order_model->add_refund_request_message($request->id, 1);
                $mail_user_id = $request->seller_id;
                $refund_url = generate_dash_url("refund_requests") . "/" . $request->id;
            } elseif ($request->seller_id == $this->auth_user->id) {
                $this->order_model->add_refund_request_message($request->id, 0);
                $mail_user_id = $request->buyer_id;
            }
            //send email
            if(!empty($mail_user_id)){
                $user = get_user($mail_user_id);
                if (!empty($this->general_settings->mail_username) && !empty($user)) {
                    $email_data = array(
                        'email_type' => 'email_general',
                        'to' => $user->email,
                        'subject' => trans("refund_request"),
                        'email_content' => trans("msg_refund_request_update_email"),
                        'email_link' => $refund_url,
                        'email_button_text' => trans("see_details")
                    );
                    $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
                }
            }
        }
        redirect($this->agent->referrer());
    }

    public function delete_request()
    {
      $id = $this->input->post('id', true);
      $data = $this->buy_model->get_buy_request($id);
      
      if ($data) {
          $this->file_model->delete_buy_images($id);
          $this->buy_model->delete_buy_request($id);
      }
      
    }
}
