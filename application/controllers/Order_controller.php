<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!$this->is_sale_active) {
            redirect(lang_base_url());
        }
        $this->order_per_page = 15;
        $this->earnings_per_page = 15;
        $this->user_id = $this->auth_user->id;
    }

    /**
     * Orders
     */
    public function orders()
    {
        $data['title'] = trans("orders");
        $data['description'] = trans("orders") . " - " . $this->app_name;
        $data['keywords'] = trans("orders") . "," . $this->app_name;
        $pagination = $this->paginate(generate_url("orders"), $this->order_model->get_orders_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_orders($this->user_id, $pagination['per_page'], $pagination['offset']);
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $this->load->view('partials/_header', $data);
        $this->load->view('order/orders', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Order
     */
    public function order($order_number)
    {
        $data['title'] = trans("orders");
        $data['description'] = trans("orders") . " - " . $this->app_name;
        $data['keywords'] = trans("orders") . "," . $this->app_name;

        $data["order"] = $this->order_model->get_order_by_order_number($order_number);
        if (empty($data["order"])) {
            redirect(lang_base_url());
        }
        if ($data["order"]->buyer_id != $this->user_id) {
            redirect(lang_base_url());
        }
        $data["order_products"] = $this->order_model->get_order_products($data["order"]->id);
        $data["last_bank_transfer"] = $this->order_admin_model->get_bank_transfer_by_order_number($data["order"]->order_number);

        $this->load->view('partials/_header', $data);
        $this->load->view('order/order', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Bank Transfer Payment Report Post
     */
    public function bank_transfer_payment_report_post()
    {
        $this->order_model->add_bank_transfer_payment_report();
        redirect($this->agent->referrer());
    }


    /**
     * Approve Order Product
     */
    public function approve_order_product_post()
    {
        $order_id = $this->input->post('order_product_id', true);
        $order_product_id = $this->input->post('order_product_id', true);
        if ($this->order_model->approve_order_product($order_product_id)) {
            //order product
            $order_product = $this->order_model->get_order_product($order_product_id);
            //add seller earnings
            $this->earnings_model->add_seller_earnings($order_product);
            //update order status
            $this->order_admin_model->update_order_status_if_completed($order_product->order_id);
        }
    }

    /**
     * Refund Requests
     */
    public function refund_requests()
    {
        $data['title'] = trans("refund_requests");
        $data['description'] = trans("refund_requests") . " - " . $this->app_name;
        $data['keywords'] = trans("refund_requests") . "," . $this->app_name;

        $num_rows = $this->order_model->get_refund_requests_count($this->user_id, 'buyer');
        $pagination = $this->paginate(generate_url("refund_requests"), $num_rows, $this->order_per_page);
        $data['refund_requests'] = $this->order_model->get_refund_requests_paginated($this->user_id, 'buyer', $pagination['per_page'], $pagination['offset']);
        $data['user_orders'] = $this->order_model->get_orders_by_buyer_id($this->user_id);
        $data['active_refund_request_ids'] = $this->order_model->get_buyer_active_refund_request_ids($this->user_id);

        $this->load->view('partials/_header', $data);
        $this->load->view('refund/refund_requests', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Refund
     */
    public function refund($id)
    {
        $data['title'] = trans("refund");
        $data['description'] = trans("refund") . " - " . $this->app_name;
        $data['keywords'] = trans("refund") . "," . $this->app_name;

        $data['refund_request'] = $this->order_model->get_refund_request($id);
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

        $this->load->view('partials/_header', $data);
        $this->load->view('refund/refund', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Submit Refund Request
     */
    public function submit_refund_request()
    {
        $order_product_id = $this->input->post('order_product_id', true);
        $order_product = $this->db->where('id', clean_number($order_product_id))->get('order_products')->row();
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


    /**
     * Cancel Order
     */
    public function cancel_order_post()
    {
        $order_id = $this->input->post('order_id', true);
        $this->order_model->cancel_order($order_id);
    }
}
