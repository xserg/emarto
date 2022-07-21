<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_admin_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Orders
     */
    public function orders()
    {
        check_permission('orders');
        $data['title'] = trans("orders");
        $data['form_action'] = admin_url() . "orders";

        $pagination = $this->paginate(admin_url() . 'orders', $this->order_admin_model->get_orders_count());
        $data['orders'] = $this->order_admin_model->get_paginated_orders($pagination['per_page'], $pagination['offset']);
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/order/orders', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Order Details
     */
    public function order_details($id)
    {
        check_permission('orders');
        $data['title'] = trans("order");

        $data['order'] = $this->order_admin_model->get_order($id);
        if (empty($data['order'])) {
            redirect(admin_url() . "orders");
        }
        $data['order_products'] = $this->order_admin_model->get_order_products($id);
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/order/order_details', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Order Options Post
     */
    public function order_options_post()
    {
        check_permission('orders');
        $order_id = $this->input->post('id', true);
        $option = $this->input->post('option', true);

        if ($option == "payment_received") {
            $this->order_admin_model->update_order_payment_received($order_id);

            $this->order_admin_model->update_payment_status_if_all_received($order_id);
            $this->order_admin_model->update_order_status_if_completed($order_id);
        }

        $this->session->set_flashdata('success', trans("msg_updated"));
        redirect($this->agent->referrer());
    }

    /**
     * Delete Order Post
     */
    public function delete_order_post()
    {
        check_permission('orders');
        $id = $this->input->post('id', true);
        if ($this->order_admin_model->delete_order($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Update Order Product Status Post
     */
    public function update_order_product_status_post()
    {
        check_permission('orders');
        $id = $this->input->post('id', true);
        $order_product = $this->order_admin_model->get_order_product($id);
        if (!empty($order_product)) {
            if ($this->order_admin_model->update_order_product_status($order_product->id)) {
                $order_status = $this->input->post('order_status', true);
                if ($order_status == "refund_approved") {
                    $this->earnings_model->refund_product($order_product);
                } else {
                    if ($order_product->product_type == "digital") {
                        if ($order_status == 'completed' || $order_status == 'payment_received') {
                            $this->order_model->add_digital_sale($order_product->product_id, $order_product->order_id);
                            //add seller earnings
                            $this->earnings_model->add_seller_earnings($order_product);
                        }
                    } else {
                        if ($order_status == 'completed') {
                            //add seller earnings
                            $this->earnings_model->add_seller_earnings($order_product);
                        }
                    }
                }
                $this->session->set_flashdata('success', trans("msg_updated"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }

            $this->order_admin_model->update_payment_status_if_all_received($order_product->order_id);
            $this->order_admin_model->update_order_status_if_completed($order_product->order_id);
        }
        redirect($this->agent->referrer() . "#t_product");
    }

    /**
     * Refund Product Post
     */
    public function refund_product_post()
    {
        check_permission('refund_requests');
        $order_product_id = $this->input->post('order_product_id', true);
        $order_product = $this->order_admin_model->get_order_product($order_product_id);
        if (!empty($order_product)) {
            $this->earnings_model->refund_product($order_product);
            $this->order_admin_model->update_order_status_if_completed($order_product->order_id);
        }
        redirect($this->agent->referrer());
    }

    /**
     * Delete Order Product Post
     */
    public function delete_order_product_post()
    {
        check_permission('orders');
        $id = $this->input->post('id', true);
        if ($this->order_admin_model->delete_order_product($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Transactions
     */
    public function transactions()
    {
        check_permission('orders');
        $data['title'] = trans("transactions");
        $data['form_action'] = admin_url() . "transactions";

        $pagination = $this->paginate(admin_url() . 'transactions', $this->transaction_model->get_transactions_count());
        $data['transactions'] = $this->transaction_model->get_paginated_transactions($pagination['per_page'], $pagination['offset']);
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/order/transactions', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Delete Transaction Post
     */
    public function delete_transaction_post()
    {
        check_permission('orders');
        $id = $this->input->post('id', true);
        if ($this->transaction_model->delete_transaction($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Bank Transfer Notifications
     */
    public function order_bank_transfers()
    {
        check_permission('orders');
        $data['title'] = trans("bank_transfer_notifications");
        $data['form_action'] = admin_url() . "order-bank-transfers";

        $pagination = $this->paginate(admin_url() . 'order-bank-transfers', $this->order_admin_model->get_bank_transfers_count());
        $data['bank_transfers'] = $this->order_admin_model->get_paginated_bank_transfers($pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/order/bank_transfers', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**f
     * Bank Transfer Options Post
     */
    public function bank_transfer_options_post()
    {
        check_permission('orders');
        $id = $this->input->post('id', true);
        $order_id = $this->input->post('order_id', true);
        $option = $this->input->post('option', true);
        if ($this->order_admin_model->update_bank_transfer_status($id, $option)) {
            if ($option == 'approved') {
                $this->order_admin_model->update_order_payment_received($order_id);
            }
            $this->order_admin_model->update_order_status_if_completed($order_id);
            $this->session->set_flashdata('success', trans("msg_updated"));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Approve Guest Order Product
     */
    public function approve_guest_order_product()
    {
        check_permission('orders');
        $order_product_id = $this->input->post('order_product_id', true);
        if ($this->order_admin_model->approve_guest_order_product($order_product_id)) {
            //order product
            $order_product = $this->order_admin_model->get_order_product($order_product_id);
            //add seller earnings
            $this->earnings_model->add_seller_earnings($order_product);
            //update order status
            $this->order_admin_model->update_order_status_if_completed($order_product->order_id);
        }
        redirect($this->agent->referrer());
    }

    /**
     * Delete Bank Transfer Post
     */
    public function delete_bank_transfer_post()
    {
        check_permission('orders');
        $id = $this->input->post('id', true);
        if ($this->order_admin_model->delete_bank_transfer($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Invoices
     */
    public function invoices()
    {
        check_permission('orders');
        $data['title'] = trans("invoices");
        $data['form_action'] = admin_url() . "invoices";

        $pagination = $this->paginate(admin_url() . 'invoices', $this->order_admin_model->get_invoices_count());
        $data['invoices'] = $this->order_admin_model->get_paginated_invoices($pagination['per_page'], $pagination['offset']);
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/order/invoices', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Digital Sales
     */
    public function digital_sales()
    {
        check_permission('digital_sales');
        $data['title'] = trans("digital_sales");
        $data['form_action'] = admin_url() . "digital-sales";
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $pagination = $this->paginate(admin_url() . 'digital-sales', $this->order_admin_model->get_digital_sales_count());
        $data['digital_sales'] = $this->order_admin_model->get_digital_sales($pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/order/digital_sales', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Delete Digital Sales Post
     */
    public function delete_digital_sales_post()
    {
        check_permission('digital_sales');
        $id = $this->input->post('id', true);
        if ($this->order_admin_model->delete_digital_sale($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /*
    *------------------------------------------------------------------------------------------
    * REFUND
    *------------------------------------------------------------------------------------------
    */

    /**
     * Refund Requests
     */
    public function refund_requests()
    {
        check_permission('refund_requests');
        $data['title'] = trans("refund_requests");
        $data['description'] = trans("refund_requests") . " - " . $this->app_name;
        $data['keywords'] = trans("refund_requests") . "," . $this->app_name;

        $data['num_rows'] = $this->order_model->get_refund_requests_count($this->auth_user->id, 'admin');
        $pagination = $this->paginate(admin_url() . "refund-requests", $data['num_rows']);
        $data['refund_requests'] = $this->order_model->get_refund_requests_paginated($this->auth_user->id, 'admin', $pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/refund/refund_requests', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Refund
     */
    public function refund($id)
    {
        check_permission('refund_requests');
        $data['title'] = trans("refund");
        $data['description'] = trans("refund") . " - " . $this->app_name;
        $data['keywords'] = trans("refund") . "," . $this->app_name;

        $data['refund_request'] = $this->order_model->get_refund_request($id);
        if (empty($data['refund_request'])) {
            redirect(admin_url() . "refund-requests");
            exit();
        }
        $data['product'] = get_order_product($data['refund_request']->order_product_id);
        if (empty($data['product'])) {
            redirect(admin_url() . "refund-requests");
            exit();
        }
        $data['messages'] = $this->order_model->get_refund_messages($id);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/refund/refund', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Approve or Decline Refund Request
     */
    public function approve_decline_refund()
    {
        check_permission('refund_requests');
        $this->order_model->approve_decline_refund();
        redirect($this->agent->referrer());
    }
}
