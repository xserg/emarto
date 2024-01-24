<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->per_page = 15;
    }

    /**
     * Index
     */
    public function index()
    {
        $this->check_vendor_permission();
        $data['title'] = get_shop_name($this->auth_user);
        $data['description'] = get_shop_name($this->auth_user) . " - " . $this->app_name;
        $data['keywords'] = get_shop_name($this->auth_user) . "," . $this->app_name;
        $data["user"] = $this->auth_user;
        $data["user_rating"] = calculate_user_rating($this->auth_user->id);
        $data["active_tab"] = "products";

        $data['active_sales_count'] = $this->order_admin_model->get_active_sales_count_by_seller($this->auth_user->id);
        $data['completed_sales_count'] = $this->order_admin_model->get_completed_sales_count_by_seller($this->auth_user->id);
        $data['total_sales_count'] = $data['active_sales_count'] + $data['completed_sales_count'];

        $data['total_pageviews_count'] = $this->product_model->get_vendor_total_pageviews_count($this->auth_user->id);
        $data['products_count'] = $this->product_model->get_user_products_count($this->auth_user->id);
        $data['latest_sales'] = $this->order_model->get_limited_sales_by_seller($this->auth_user->id, 6);
        $data['most_viewed_products'] = $this->product_model->get_vendor_most_viewed_products($this->auth_user->id, 6);
        $data['latest_comments'] = $this->comment_model->get_paginated_vendor_comments($this->auth_user->id, 6, 0);
        $data['latest_reviews'] = $this->review_model->get_paginated_vendor_reviews($this->auth_user->id, 6, 0);
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $data['sales_sum'] = $this->order_admin_model->get_sales_sum_by_month($this->auth_user->id);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /*
    *------------------------------------------------------------------------------------------
    * PRODUCTS
    *------------------------------------------------------------------------------------------
    */

    /**
     * Add Product
     */
    public function add_product()
    {
        $this->check_vendor_permission();
        $this->is_cancel_account();
        $data['title'] = trans("add_product");
        $data['description'] = trans("add_product") . " - " . $this->app_name;
        $data['keywords'] = trans("add_product") . "," . $this->app_name;

        $data['modesy_images'] = $this->file_model->get_sess_product_images_array();
        $data["file_manager_images"] = $this->file_model->get_user_file_manager_images($this->auth_user->id);
        $data["active_product_system_array"] = $this->get_activated_product_system();
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $view = !$this->membership_model->is_allowed_adding_product() ? 'plan_expired' : 'add_product';

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/' . $view, $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Add Product Post
     */
    public function add_product_post()
    {
        $this->check_vendor_permission();
        $this->is_cancel_account();
        if (!$this->membership_model->is_allowed_adding_product()) {
            $this->session->set_flashdata('error', trans("msg_plan_expired"));
            redirect($this->agent->referrer());
            exit();
        }
        //validate title
        if (empty(trim($this->input->post('title_' . $this->selected_lang->id, true)))) {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
        //add product
        if ($this->product_model->add_product()) {
            //last id
            $last_id = $this->db->insert_id();
            //add product title and desc
            $this->product_model->add_product_title_desc($last_id);
            //update slug
            $this->product_model->update_slug($last_id);
            //add product images
            $this->file_model->add_product_images($last_id);

            redirect(generate_dash_url("product", "product_details") . '/' . $last_id);
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Edit Product
     */
    public function edit_product($id)
    {
        $this->check_vendor_permission(true);
        $this->is_cancel_account();
        $data["product"] = $this->product_admin_model->get_product($id);
        if (empty($data["product"])) {
            redirect($this->agent->referrer());
        }
        if ($data["product"]->is_deleted == 1) {
            if (!has_permission('products')) {
                redirect($this->agent->referrer());
                exit();
            }
        }
        if ($data["product"]->user_id != $this->auth_user->id && !has_permission('products')) {
            redirect($this->agent->referrer());
            exit();
        }

        $data['title'] = $data["product"]->is_draft == 1 ? trans("add_product") : trans("edit_product");
        $data['description'] = $data['title'] . " - " . $this->app_name;
        $data['keywords'] = $data['title'] . "," . $this->app_name;

        $data['category'] = $this->category_model->get_category($data["product"]->category_id);
        $data['parent_categories_array'] = $this->category_model->get_parent_categories_tree($data['category']);
        $data['modesy_images'] = $this->file_model->get_product_images($data["product"]->id);
        //$data['all_categories'] = $this->category_model->get_categories_ordered_by_name();
        $data["file_manager_images"] = $this->file_model->get_user_file_manager_images($this->auth_user->id);
        $data["active_product_system_array"] = $this->get_activated_product_system();
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/edit_product', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Edit Product Post
     */
    public function edit_product_post()
    {
        $this->check_vendor_permission(true);
        $this->is_cancel_account();
        //product id
        $product_id = $this->input->post('id', true);
        //user id
        $user_id = 0;
        $product = $this->product_model->get_product_by_id($product_id);
        if (!empty($product)) {
            if ($product->user_id != $this->auth_user->id && !has_permission('products')) {
                redirect($this->agent->referrer());
                exit();
            }

            //check slug is unique
            $slug = $product->slug;
            if (is_admin()) {
                $slug = $this->input->post('slug', true);
                if (empty($slug)) {
                    $slug = "product-" . $product->id;
                }
                if ($this->db->where('id !=', $product->id)->where('slug', $slug)->get('products')->num_rows() > 0) {
                    $this->session->set_flashdata('error', trans("msg_product_slug_used"));
                    redirect($this->agent->referrer());
                    exit();
                }
            }

            if ($this->product_model->edit_product($product, $slug)) {
                //edit product title and desc
                $this->product_model->edit_product_title_desc($product_id);
                if ($product->is_draft == 1) {
                    redirect(generate_dash_url("product", "product_details") . '/' . $product_id);
                } else {
                    //reset cache
                    reset_cache_data_on_change();
                    reset_user_cache_data($product->user_id);
                    reset_product_img_cache_data($product_id);

                    $this->session->set_flashdata('success', trans("msg_updated"));
                    redirect($this->agent->referrer());
                }
            }
        }
        $this->session->set_flashdata('error', trans("msg_error"));
        redirect($this->agent->referrer());
    }

    /**
     * Edit Product Details
     */
    public function edit_product_details($id)
    {
        $this->check_vendor_permission(true);
        $this->is_cancel_account();
        $data['product'] = $this->product_admin_model->get_product($id);
        if (empty($data['product'])) {
            redirect($this->agent->referrer());
            exit();
        }
        if (!has_permission('products') && $this->auth_user->id != $data['product']->user_id) {
            redirect($this->agent->referrer());
            exit();
        }

        if ($data['product']->is_draft == 1) {
            $data['title'] = trans("add_product");
            $data['description'] = trans("add_product") . " - " . $this->app_name;
            $data['keywords'] = trans("add_product") . "," . $this->app_name;
        } else {
            $data['title'] = trans("edit_product");
            $data['description'] = trans("edit_product") . " - " . $this->app_name;
            $data['keywords'] = trans("edit_product") . "," . $this->app_name;
        }

        $data["custom_fields"] = $this->field_model->get_custom_fields_by_category($data["product"]->category_id);
        $data["product_variations"] = $this->variation_model->get_product_variations($data["product"]->id);
        $data["user_variations"] = $this->variation_model->get_variation_by_user_id($data["product"]->user_id);
        $data['product_settings'] = $this->settings_model->get_product_settings();
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $data['license_keys'] = $this->product_model->get_license_keys($data["product"]->id);

        //shipping
        $data['shipping_status'] = $this->product_settings->marketplace_shipping;
        if ($data["product"]->listing_type == 'ordinary_listing' || $data["product"]->product_type != 'physical') {
            $data['shipping_status'] = 0;
        }

        //$data['shipping_classes'] = $this->shipping_model->get_active_shipping_classes($this->auth_user->id);
        //$data['shipping_classes'] = $this->shipping_model->get_default_shipping_classes();
        //$data['shipping_delivery_times'] = $this->shipping_model->get_shipping_delivery_times($this->auth_user->id);
        $data['shipping_delivery_times'] = $this->shipping_model->get_default_shipping_delivery_times();

        //echo '<pre>';
        //print_r($data['shipping_delivery_times']);

        $shipping_zones = $this->shipping_model->get_shipping_zones($this->auth_user->id);

        $data['show_shipping_options_warning'] = false;
        if ($data['shipping_status'] == 1 && empty($shipping_zones)) {
            $data['show_shipping_options_warning'] = true;
        }

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/edit_product_details', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Edit Product Details Post
     */
    public function edit_product_details_post()
    {
        $this->check_vendor_permission(true);
        $this->is_cancel_account();
        $product_id = $this->input->post('id', true);
        $product = $this->product_admin_model->get_product($product_id);
        if (empty($product)) {
            redirect($this->agent->referrer());
            exit();
        }
        if (!has_permission('products') && $this->auth_user->id != $product->user_id) {
            redirect($this->agent->referrer());
            exit();
        }
        //check digital file
        if ($product->product_type == "digital" && $product->listing_type != 'license_key') {
            if ($this->db->where('product_id', $product->id)->get('digital_files')->num_rows() <= 0) {
                $this->session->set_flashdata('error', trans("digital_file_required"));
                redirect($this->agent->referrer());
                exit();
            }
        }
        if ($this->product_model->edit_product_details($product_id)) {
            //edit custom fields
            $this->product_model->update_product_custom_fields($product_id);

            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data($this->auth_user->id);

            if ($product->is_draft != 1) {
                $this->session->set_flashdata('success', trans("msg_updated"));
                redirect($this->agent->referrer());
            } else {
                //if draft
                if ($this->input->post('submit', true) == 'save_as_draft') {
                    $this->session->set_flashdata('success', trans("draft_added"));
                } else {
                    if ($this->general_settings->approve_before_publishing == 1 && !is_admin()) {
                        $this->session->set_flashdata('success', trans("product_added") . " " . trans("product_approve_published") . " <a href='" . generate_product_url($product) . "' class='link-view-product'>" . trans("view_product") . "</a>");
                    } else {
                        $this->session->set_flashdata('success', trans("product_added") . " <a href='" . generate_product_url($product) . "' class='link-view-product' target='_blank'>" . trans("view_product") . "</a>");
                    }
                    //send email
                    if ($this->general_settings->send_email_new_product == 1) {
                        $email_data = array(
                            'email_type' => 'new_product',
                            'product_id' => $product->id
                        );
                        $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
                    }
                }
                redirect(generate_dash_url("add_product"));
            }
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    //get activated product system
    public function get_activated_product_system()
    {
        $array = array(
            'active_system_count' => 0,
            'active_system_value' => "",
        );
        if ($this->general_settings->marketplace_system == 1) {
            $array['active_system_count'] = $array['active_system_count'] + 1;
            $array['active_system_value'] = "sell_on_site";
        }
        if ($this->general_settings->classified_ads_system == 1) {
            $array['active_system_count'] = $array['active_system_count'] + 1;
            $array['active_system_value'] = "ordinary_listing";
        }
        if ($this->general_settings->bidding_system == 1) {
            $array['active_system_count'] = $array['active_system_count'] + 1;
            $array['active_system_value'] = "bidding";
        }
        return $array;
    }

    /**
     * Products
     */
    public function products()
    {
        $this->check_vendor_permission();
        $data['title'] = trans("products");
        $data['description'] = trans("products") . " - " . $this->app_name;
        $data['keywords'] = trans("products") . "," . $this->app_name;
        $data['page_url'] = generate_dash_url("products");
        $data['promote_status'] = $this->general_settings->promoted_products == 1 ? 1 : 0;
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $data['num_rows'] = $this->product_model->get_paginated_user_products_count($this->auth_user->id, 'active');
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['products'] = $this->product_model->get_paginated_user_products($this->auth_user->id, 'active', $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/products', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Pending Products
     */
    public function pending_products()
    {
        $this->check_vendor_permission();
        $data['title'] = trans("pending_products");
        $data['description'] = trans("pending_products") . " - " . $this->app_name;
        $data['keywords'] = trans("pending_products") . "," . $this->app_name;
        $data['page_url'] = generate_dash_url("pending_products");
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $data['num_rows'] = $this->product_model->get_paginated_user_products_count($this->auth_user->id, 'pending');
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['products'] = $this->product_model->get_paginated_user_products($this->auth_user->id, 'pending', $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/pending_products', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Hidden Products
     */
    public function hidden_products()
    {
        $this->check_vendor_permission();
        $data['title'] = trans("hidden_products");
        $data['description'] = trans("hidden_products") . " - " . $this->app_name;
        $data['keywords'] = trans("hidden_products") . "," . $this->app_name;
        $data['page_url'] = generate_dash_url("hidden_products");
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $data['num_rows'] = $this->product_model->get_paginated_user_products_count($this->auth_user->id, 'hidden');
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['products'] = $this->product_model->get_paginated_user_products($this->auth_user->id, 'hidden', $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/products', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Sold Products
     */
    public function sold_products()
    {
        $this->check_vendor_permission();
        $data['title'] = trans("sold_products");
        $data['description'] = trans("sold_products") . " - " . $this->app_name;
        $data['keywords'] = trans("sold_products") . "," . $this->app_name;
        $data['page_url'] = generate_dash_url("sold_products");
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $data['num_rows'] = $this->product_model->get_paginated_user_products_count($this->auth_user->id, 'sold');
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['products'] = $this->product_model->get_paginated_user_products($this->auth_user->id, 'sold', $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/products', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Drafts
     */
    public function drafts()
    {
        $this->check_vendor_permission();
        $data['title'] = trans("drafts");
        $data['description'] = trans("drafts") . " - " . $this->app_name;
        $data['keywords'] = trans("drafts") . "," . $this->app_name;
        $data['page_url'] = generate_dash_url("drafts");
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $data['num_rows'] = $this->product_model->get_paginated_user_products_count($this->auth_user->id, 'draft');
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['products'] = $this->product_model->get_paginated_user_products($this->auth_user->id, 'draft', $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/products', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Expired Products
     */
    public function expired_products()
    {
        $this->check_vendor_permission();
        if ($this->general_settings->membership_plans_system != 1) {
            redirect(dashboard_url());
            exit();
        }

        $data['title'] = trans("expired_products");
        $data['description'] = trans("expired_products") . " - " . $this->app_name;
        $data['keywords'] = trans("expired_products") . "," . $this->app_name;
        $data['page_url'] = generate_dash_url("expired_products");
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $data['num_rows'] = $this->product_model->get_paginated_user_products_count($this->auth_user->id, 'expired');
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['products'] = $this->product_model->get_paginated_user_products($this->auth_user->id, 'expired', $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/products', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Delete Product
     */
    public function delete_product()
    {
        $this->check_vendor_permission();
        $this->is_cancel_account();
        $id = $this->input->post('id', true);
        //user id
        $user_id = 0;
        $product = $this->product_admin_model->get_product($id);
        if (!empty($product)) {
            $user_id = $product->user_id;
        }
        $result = false;
        if (has_permission('products') || $this->auth_user->id == $user_id) {
            if ($product->is_draft == 1) {
                $result = $this->product_admin_model->delete_product_permanently($id);
            } else {
                $result = $this->product_model->delete_product($id);
            }
        }
        if ($result) {
            $this->session->set_flashdata('success', trans("msg_product_deleted"));
            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data($user_id);
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    //get subcategories
    public function get_subcategories()
    {
        $parent_id = $this->input->post('parent_id', true);
        if (!empty($parent_id)) {
            $subcategories = $this->category_model->get_subcategories_by_parent_id($parent_id);
            foreach ($subcategories as $item) {
                echo '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        }
    }

    /*
    *------------------------------------------------------------------------------------------
    * LICENSE KEYS
    *------------------------------------------------------------------------------------------
    */
    //add license keys
    public function add_license_keys()
    {
        post_method();
        $this->check_vendor_permission(true);
        $product_id = $this->input->post('product_id', true);
        $product = $this->product_model->get_product_by_id($product_id);

        if (!empty($product)) {
            if ($this->auth_user->id == $product->user_id || has_permission('products')) {
                $this->product_model->add_license_keys($product_id);
                $this->session->set_flashdata('success', trans("msg_add_license_keys"));
                $data = array(
                    'result' => 1,
                    'success_message' => $this->load->view('dashboard/includes/_messages', '', true)
                );
                echo json_encode($data);
                reset_flash_data();
            }
        }
    }

    //delete license key
    public function delete_license_key()
    {
        post_method();
        $this->check_vendor_permission(true);
        $id = $this->input->post('id', true);
        $product_id = $this->input->post('product_id', true);
        $product = $this->product_model->get_product_by_id($product_id);
        if (!empty($product)) {
            if ($this->auth_user->id == $product->user_id || has_permission('products')) {
                $this->product_model->delete_license_key($id);
            }
        }
    }

    //refresh license keys list
    public function refresh_license_keys_list()
    {
        post_method();
        $product_id = $this->input->post('product_id', true);
        $data['product'] = $this->product_model->get_product_by_id($product_id);
        if (!empty($data['product'])) {
            if ($this->auth_user->id == $data['product']->user_id || has_permission('products')) {
                $data['license_keys'] = $this->product_model->get_license_keys($product_id);
                $this->load->view("dashboard/product/license/_license_keys_list", $data);
            }
        }
    }

    /*
    *------------------------------------------------------------------------------------------
    * CSV BULK IMPORT
    *------------------------------------------------------------------------------------------
    */

    /**
     * Bulk Product Upload
     */
    public function bulk_product_upload()
    {
        $this->check_vendor_permission();
        $this->is_cancel_account();
        $data['title'] = trans("bulk_product_upload");
        $view = !$this->membership_model->is_allowed_adding_product() ? 'plan_expired' : 'bulk_product_upload';
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        if (!has_permission('products') && $this->general_settings->vendor_bulk_product_upload != 1) {
            redirect(dashboard_url());
            exit();
        }

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/product/' . $view, $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Download CSV Files Post
     */
    public function download_csv_files_post()
    {
        post_method();
        $this->check_vendor_permission();
        $submit = $this->input->post('submit', true);
        if ($submit == 'csv_template') {
            $this->load->helper('download');
            force_download(FCPATH . "assets/file/csv_product_template.csv", NULL);
        } elseif ($submit == 'csv_example') {
            $this->load->helper('download');
            force_download(FCPATH . "assets/file/csv_product_example.csv", NULL);
        }
    }

    /**
     * Generate CSV Object Post
     */
    public function generate_csv_object_post()
    {
        $this->check_vendor_permission();
        //delete old txt files
        $files = glob(FCPATH . 'uploads/temp/*.txt');
        $now = time();
        if (!empty($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    if ($now - filemtime($file) >= 60 * 60 * 24) {
                        @unlink($file);
                    }
                }
            }
        }
        $file = null;
        if (isset($_FILES['file'])) {
            if (!empty($_FILES['file']['name'])) {
                $file = $_FILES['file'];
            }
        }
        $file_path = "";
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'csv';
        $config['file_name'] = uniqid();
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file')) {
            $data = $this->upload->data();
            if (isset($data['full_path'])) {
                $file_path = $data['full_path'];
            }
        }
        if (!empty($file_path)) {
            $csv_object = $this->product_admin_model->generate_csv_object($file_path);
            if (!empty($csv_object)) {
                $data = array(
                    'result' => 1,
                    'number_of_items' => $csv_object->number_of_items,
                    'txt_file_name' => $csv_object->txt_file_name,
                );
                echo json_encode($data);
                exit();
            }
        }
        $data = array(
            'result' => 0
        );
        echo json_encode($data);
    }

    /**
     * Import CSV Item Post
     */
    public function import_csv_item_post()
    {
        $this->check_vendor_permission();
        $txt_file_name = $this->input->post('txt_file_name', true);
        $index = $this->input->post('index', true);

        $name = $this->product_admin_model->import_csv_item($txt_file_name, $index);
        if (!empty($name)) {
            $data = array(
                'result' => 1,
                'name' => $name,
                'index' => $index
            );
            echo json_encode($data);
        } else {
            $data = array(
                'result' => 0,
                'index' => $index
            );
            echo json_encode($data);
        }
    }

    /*
    *------------------------------------------------------------------------------------------
    * PROMOTE
    *------------------------------------------------------------------------------------------
    */

    /**
     * Pricing Post
     */
    public function pricing_post()
    {
        $this->check_vendor_permission();
        $product_id = $this->input->post('product_id', true);
        $product = $this->product_model->get_product_by_id($product_id);
        if (!empty($product)) {
            if ($product->user_id != $this->auth_user->id) {
                $this->session->set_flashdata('error', trans("invalid_attempt"));
                redirect($this->agent->referrer());
                exit();
            }

            $plan_type = $this->input->post('plan_type', true);
            $price_per_day = get_price($this->payment_settings->price_per_day, 'decimal');
            $price_per_month = get_price($this->payment_settings->price_per_month, 'decimal');

            $day_count = $this->input->post('day_count', true);
            $month_count = $this->input->post('month_count', true);
            $total_amount = 0;
            if ($plan_type == "daily") {
                $total_amount = number_format($day_count * $price_per_day, 2, ".", "") * 100;
                $purchased_plan = trans("daily_plan") . " (" . $day_count . " " . trans("days") . ")";
            }
            if ($plan_type == "monthly") {
                $day_count = $month_count * 30;
                $total_amount = number_format($month_count * $price_per_month, 2, ".", "") * 100;
                $purchased_plan = trans("monthly_plan") . " (" . $day_count . " " . trans("days") . ")";
            }
            $data = new stdClass();
            $data->plan_type = $this->input->post('plan_type', true);
            $data->product_id = $product_id;
            $data->day_count = $day_count;
            $data->month_count = $month_count;
            $data->total_amount = get_price($total_amount, 'decimal');
            $data->purchased_plan = $purchased_plan;

            if ($this->payment_settings->free_product_promotion == 1) {
                $this->promote_model->add_to_promoted_products($data);
                redirect($this->agent->referrer());
            } else {
                $this->session->set_userdata('modesy_selected_promoted_plan', $data);
                redirect(generate_url("cart", "payment_method") . "?payment_type=promote");
            }
        }
        $this->session->set_flashdata('error', trans("invalid_attempt"));
        redirect($this->agent->referrer());
    }

    /*
    *------------------------------------------------------------------------------------------
    * SALES
    *------------------------------------------------------------------------------------------
    */

    /**
     * Sales
     */
    public function sales()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data['active_page'] = "sales";
        $data['page_url'] = generate_dash_url("sales");
        $data['lang_settings'] = lang_settings();
        $data['num_rows'] = $this->order_model->get_sales_count($this->auth_user->id);
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['sales'] = $this->order_model->get_paginated_sales($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/sales/sales', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Completed Sales
     */
    public function completed_sales()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("completed_sales");
        $data['description'] = trans("completed_sales") . " - " . $this->app_name;
        $data['keywords'] = trans("completed_sales") . "," . $this->app_name;
        $data['active_page'] = "completed_sales";
        $data['page_url'] = generate_dash_url("completed_sales");
        $data['lang_settings'] = lang_settings();
        $data['num_rows'] = $this->order_model->get_completed_sales_count($this->auth_user->id);
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['sales'] = $this->order_model->get_paginated_completed_sales($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/sales/sales', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Cancelled Sales
     */
    public function cancelled_sales()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("cancelled_sales");
        $data['description'] = trans("cancelled_sales") . " - " . $this->app_name;
        $data['keywords'] = trans("cancelled_sales") . "," . $this->app_name;
        $data['active_page'] = "cancelled_sales";
        $data['page_url'] = generate_dash_url("cancelled_sales");
        $data['lang_settings'] = lang_settings();
        $data['num_rows'] = $this->order_model->get_cancelled_sales_count($this->auth_user->id);
        $pagination = $this->paginate($data['page_url'], $data['num_rows'], $this->per_page);
        $data['sales'] = $this->order_model->get_paginated_cancelled_sales($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/sales/sales', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Sale
     */
    public function sale($order_number)
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "";
        $data["order"] = $this->order_model->get_order_by_order_number($order_number);
        if (empty($data["order"])) {
            redirect(dashboard_url());
        }
        if (!$this->order_model->check_order_seller($data["order"]->id)) {
            redirect(dashboard_url());
        }
        $data["order_products"] = $this->order_model->get_order_products($data["order"]->id);
        $data['lang_settings'] = lang_settings();
        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/sales/sale', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Update Order Product Status Post
     */
    public function update_order_product_status_post()
    {
        $this->check_vendor_permission();
        $id = $this->input->post('id', true);
        $order_product = $this->order_model->get_order_product($id);
        if ($this->auth_user->id != $order_product->seller_id) {
            redirect($this->agent->referrer());
            exit();
        }
        if (!empty($order_product)) {
            if ($this->order_model->update_order_product_status($id)) {
                $this->order_admin_model->update_order_status_if_completed($order_product->order_id);
            }
        }
        redirect($this->agent->referrer());
    }

    /*
    *------------------------------------------------------------------------------------------
    * COUPONS
    *------------------------------------------------------------------------------------------
    */

    /**
     * Coupons
     */
    public function coupons()
    {
        $this->check_vendor_permission();
        $this->load->model('coupon_model');
        $data['title'] = trans("coupons");
        $data['description'] = trans("coupons") . " - " . $this->app_name;
        $data['keywords'] = trans("coupons") . "," . $this->app_name;

        $data['num_rows'] = $this->coupon_model->get_coupons_count($this->auth_user->id);
        $pagination = $this->paginate(generate_dash_url("coupons"), $data['num_rows'], $this->per_page);
        $data['coupons'] = $this->coupon_model->get_coupons_paginated($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/coupon/coupons', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Add Coupon
     */
    public function add_coupon()
    {
        $this->check_vendor_permission();
        $data['title'] = trans("add_coupon");
        $data['description'] = trans("add_coupon") . " - " . $this->app_name;
        $data['keywords'] = trans("add_coupon") . "," . $this->app_name;

        $data['parent_categories'] = $this->category_model->get_parent_categories();
        $data['categories'] = $this->category_model->get_vendor_categories(null, $this->auth_user->id, true, false);
        $data['category_ids'] = array();
        if (!empty($data['categories']) && !empty($data['categories'][0])) {
            foreach ($data['categories'] as $item) {
                array_push($data['category_ids'], $item->id);
            }
        }

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/coupon/add_coupon', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Add Coupon Post
     */
    public function add_coupon_post()
    {
        $this->check_vendor_permission();
        $this->load->model('coupon_model');
        //validate inputs
        $this->form_validation->set_rules('coupon_code', trans("coupon_code"), 'required|max_length[49]');
        $this->form_validation->set_rules('discount_rate', trans("discount_rate"), 'required');
        $this->form_validation->set_rules('coupon_count', trans("number_of_coupons"), 'required');
        $this->form_validation->set_rules('expiry_date', trans("expiry_date"), 'required');
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->coupon_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('selected_products_ids', $this->coupon_model->get_selected_products_array());
            $code = $this->input->post('coupon_code', true);
            if (!empty($this->coupon_model->get_coupon_by_code($code))) {
                $this->session->set_flashdata('form_data', $this->coupon_model->input_values());
                $this->session->set_flashdata('error', trans("msg_coupon_code_added_before"));
                redirect($this->agent->referrer());
                exit();
            }
            if ($this->coupon_model->add_coupon()) {
                $this->session->set_flashdata('success', trans("msg_added"));
                $this->session->set_flashdata('reset_checkbox', 1);
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        }
        redirect($this->agent->referrer());
    }

    /**
     * Edit Coupon
     */
    public function edit_coupon($id)
    {
        $this->check_vendor_permission();
        $this->load->model('coupon_model');
        $data['title'] = trans("edit_coupon");
        $data['description'] = trans("edit_coupon") . " - " . $this->app_name;
        $data['keywords'] = trans("edit_coupon") . "," . $this->app_name;

        $data['coupon'] = $this->coupon_model->get_coupon($id);
        if (empty($data['coupon'])) {
            redirect(generate_dash_url("coupons"));
            exit();
        }
        if ($data['coupon']->seller_id != $this->auth_user->id) {
            redirect(generate_dash_url("coupons"));
            exit();
        }

        $data['parent_categories'] = $this->category_model->get_parent_categories();
        $data['categories'] = $this->category_model->get_vendor_categories(null, $this->auth_user->id, true, false);
        $data['category_ids'] = array();
        if (!empty($data['categories']) && !empty($data['categories'][0])) {
            foreach ($data['categories'] as $item) {
                array_push($data['category_ids'], $item->id);
            }
        }

        $data['selected_categories'] = explode(',', $data['coupon']->category_ids);
        if (empty($data['selected_categories'])) {
            $data['selected_categories'] = array();
        }
        $data['selected_products'] = array();
        $selected_products = $this->coupon_model->get_coupon_products($data['coupon']->id);
        if (!empty($selected_products)) {
            foreach ($selected_products as $item) {
                array_push($data['selected_products'], $item->product_id);
            }
        }

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/coupon/edit_coupon', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Edit Coupon Post
     */
    public function edit_coupon_post()
    {
        $this->check_vendor_permission();
        $this->load->model('coupon_model');
        //validate inputs
        $this->form_validation->set_rules('coupon_code', trans("coupon_code"), 'required|max_length[49]');
        $this->form_validation->set_rules('discount_rate', trans("discount_rate"), 'required');
        $this->form_validation->set_rules('coupon_count', trans("number_of_coupons"), 'required');
        $this->form_validation->set_rules('expiry_date', trans("expiry_date"), 'required');
        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->coupon_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $coupon_id = $this->input->post('id', true);
            $coupon = $this->coupon_model->get_coupon($coupon_id);
            if (empty($coupon)) {
                redirect(generate_dash_url("coupons"));
                exit();
            }
            if ($coupon->seller_id != $this->auth_user->id) {
                redirect(generate_dash_url("coupons"));
                exit();
            }
            $code = $this->input->post('coupon_code', true);
            $coupon_by_code = $this->coupon_model->get_coupon_by_code($code);
            if (!empty($coupon_by_code) && $coupon_by_code->id != $coupon->id) {
                $this->session->set_flashdata('error', trans("msg_coupon_code_added_before"));
                redirect($this->agent->referrer());
                exit();
            }
            if ($this->coupon_model->edit_coupon($coupon_id)) {
                $this->session->set_flashdata('success', trans("msg_updated"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        }
        redirect($this->agent->referrer());
    }

    /**
     * Delete Coupon Post
     */
    public function delete_coupon_post()
    {
        $this->check_vendor_permission();
        $this->load->model('coupon_model');
        $id = $this->input->post('id', true);
        $sys_lang_id = $this->input->post('sys_lang_id', true);

        $coupon = $this->coupon_model->get_coupon($id);
        if (empty($coupon)) {
            exit();
        }
        if ($coupon->seller_id != $this->auth_user->id) {
            exit();
        }
        if ($this->coupon_model->delete_coupon($coupon)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        exit();
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
        $this->check_vendor_permission();
        $data['title'] = trans("refund_requests");
        $data['description'] = trans("refund_requests") . " - " . $this->app_name;
        $data['keywords'] = trans("refund_requests") . "," . $this->app_name;

        $data['num_rows'] = $this->order_model->get_refund_requests_count($this->auth_user->id, 'seller');
        $pagination = $this->paginate(generate_dash_url("refund_requests"), $data['num_rows'], $this->per_page);
        $data['refund_requests'] = $this->order_model->get_refund_requests_paginated($this->auth_user->id, 'seller', $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/refund/refund_requests', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Refund
     */
    public function refund($id)
    {
        $this->check_vendor_permission();
        $data['title'] = trans("refund");
        $data['description'] = trans("refund") . " - " . $this->app_name;
        $data['keywords'] = trans("refund") . "," . $this->app_name;

        $data['refund_request'] = $this->order_model->get_refund_request($id);
        if (empty($data['refund_request']) || $data['refund_request']->seller_id != $this->auth_user->id) {
            redirect(generate_dash_url("refund_requests"));
            exit();
        }
        $data['product'] = get_order_product($data['refund_request']->order_product_id);
        if (empty($data['product'])) {
            redirect(generate_dash_url("refund_requests"));
            exit();
        }
        $data['messages'] = $this->order_model->get_refund_messages($id);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/refund/refund', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Approve or Decline Refund Request
     */
    public function approve_decline_refund()
    {
        $this->check_vendor_permission();
        $this->order_model->approve_decline_refund();
        redirect($this->agent->referrer());
    }


    /*
    *------------------------------------------------------------------------------------------
    * EARNINGS
    *------------------------------------------------------------------------------------------
    */

    /**
     * Earnings
     */
    public function earnings()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("earnings");
        $data['description'] = trans("earnings") . " - " . $this->app_name;
        $data['keywords'] = trans("earnings") . "," . $this->app_name;
        $data['lang_settings'] = lang_settings();
        $data['num_rows'] = $this->earnings_model->get_earnings_count($this->auth_user->id);
        $pagination = $this->paginate(generate_dash_url('earnings'), $data['num_rows'], $this->per_page);
        $data['earnings'] = $this->earnings_model->get_paginated_earnings($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/earnings/earnings', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Payouts
     */
    public function payouts()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("payouts");
        $data['description'] = trans("payouts") . " - " . $this->app_name;
        $data['keywords'] = trans("payouts") . "," . $this->app_name;
        $data['lang_settings'] = lang_settings();
        $data['num_rows'] = $this->earnings_model->get_payouts_count($this->auth_user->id);
        $pagination = $this->paginate(generate_dash_url('payouts'), $data['num_rows'], $this->per_page);
        $data['payouts'] = $this->earnings_model->get_paginated_payouts($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/earnings/payouts', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Withdraw Money
     */
    public function withdraw_money()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("withdraw_money");
        $data['description'] = trans("withdraw_money") . " - " . $this->app_name;
        $data['keywords'] = trans("withdraw_money") . "," . $this->app_name;
        $data['lang_settings'] = lang_settings();
        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/earnings/withdraw_money', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Withdraw Money Post
     */
    public function withdraw_money_post()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data = array(
            'user_id' => $this->auth_user->id,
            'payout_method' => $this->input->post('payout_method', true),
            'amount' => $this->input->post('amount', true),
            'currency' => $this->input->post('currency', true),
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );
        $data["amount"] = get_price($data["amount"], 'database');

        //check active payouts
        $active_payouts = $this->earnings_model->get_active_payouts($this->auth_user->id);
        if (!empty($active_payouts)) {
            $this->session->set_flashdata('error', trans("active_payment_request_error"));
            redirect($this->agent->referrer());
        }

        $min = 0;
        if ($data["payout_method"] == "paypal") {
            //check PayPal email
            $payout_paypal_email = $this->earnings_model->get_user_payout_account($this->auth_user->id);
            if (empty($payout_paypal_email) || empty($payout_paypal_email->payout_paypal_email)) {
                $this->session->set_flashdata('error', trans("msg_payout_paypal_error"));
                redirect($this->agent->referrer());
            }
            $min = $this->payment_settings->min_payout_paypal;
        }
        if ($data["payout_method"] == "bitcoin") {
            //check bitcoin address
            $payout_bitcoin = $this->earnings_model->get_user_payout_account($this->auth_user->id);
            if (empty($payout_bitcoin) || empty($payout_bitcoin->payout_bitcoin_address)) {
                $this->session->set_flashdata('error', trans("msg_payout_bitcoin_address_error"));
                redirect($this->agent->referrer());
            }
            $min = $this->payment_settings->min_payout_bitcoin;
        }
        if ($data["payout_method"] == "iban") {
            $min = $this->payment_settings->min_payout_iban;
        }
        if ($data["payout_method"] == "swift") {
            $min = $this->payment_settings->min_payout_swift;
        }

        if ($data["amount"] <= 0) {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
        if ($data["amount"] < $min) {
            $this->session->set_flashdata('error', trans("invalid_withdrawal_amount"));
            redirect($this->agent->referrer());
        }
        if ($data["amount"] > $this->auth_user->balance) {
            $this->session->set_flashdata('error', trans("invalid_withdrawal_amount"));
            redirect($this->agent->referrer());
        }
        if (!$this->earnings_model->withdraw_money($data)) {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
        $this->session->set_flashdata('success', trans("msg_request_sent"));
        redirect($this->agent->referrer());
    }

    /**
     * Set Payout Account
     */
    public function set_payout_account()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("set_payout_account");
        $data['description'] = trans("set_payout_account") . " - " . $this->app_name;
        $data['keywords'] = trans("set_payout_account") . "," . $this->app_name;

        $data['user_payout'] = $this->earnings_model->get_user_payout_account($this->auth_user->id);
        if (empty($this->session->flashdata('msg_payout'))) {
            if ($this->payment_settings->payout_paypal_enabled) {
                $this->session->set_flashdata('msg_payout', "paypal");
            } elseif ($this->payment_settings->payout_iban_enabled) {
                $this->session->set_flashdata('msg_payout', "iban");
            } elseif ($this->payment_settings->payout_swift_enabled) {
                $this->session->set_flashdata('msg_payout', "swift");
            }
        }

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/earnings/set_payout_account', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Set Paypal Payout Account Post
     */
    public function set_paypal_payout_account_post()
    {
        $this->check_vendor_permission();
        if ($this->earnings_model->set_paypal_payout_account($this->auth_user->id)) {
            $this->session->set_flashdata('msg_payout', "paypal");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('msg_payout', "paypal");
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Set Bitcoin Payout Account Post
     */
    public function set_bitcoin_payout_account_post()
    {
        $this->check_vendor_permission();
        if ($this->earnings_model->set_bitcoin_payout_account($this->auth_user->id)) {
            $this->session->set_flashdata('msg_payout', "bitcoin");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('msg_payout', "bitcoin");
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Set IBAN Payout Account Post
     */
    public function set_iban_payout_account_post()
    {
        $this->check_vendor_permission();
        if ($this->earnings_model->set_iban_payout_account($this->auth_user->id)) {
            $this->session->set_flashdata('msg_payout', "iban");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('msg_payout', "iban");
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Set SWIFT Payout Account Post
     */
    public function set_swift_payout_account_post()
    {
        $this->check_vendor_permission();
        if ($this->earnings_model->set_swift_payout_account($this->auth_user->id)) {
            $this->session->set_flashdata('msg_payout', "swift");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('msg_payout', "swift");
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /*
    *------------------------------------------------------------------------------------------
    * QUOTE REQUESTS
    *------------------------------------------------------------------------------------------
    */

    /**
     * Quote Requests
     */
    public function quote_requests()
    {
        $this->check_vendor_permission();
        $this->load->model('bidding_model');
        if (!is_bidding_system_active()) {
            redirect(dashboard_url());
        }
        $data['title'] = trans("quote_requests");
        $data['description'] = trans("quote_requests") . " - " . $this->app_name;
        $data['keywords'] = trans("quote_requests") . "," . $this->app_name;
        $data['lang_settings'] = lang_settings();
        $data['num_rows'] = $this->bidding_model->get_vendor_quote_requests_count($this->auth_user->id);
        $pagination = $this->paginate(generate_dash_url("quote_requests"), $data['num_rows'], $this->per_page);
        $data['quote_requests'] = $this->bidding_model->get_paginated_vendor_quote_requests($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/quote_requests', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Submit Quote
     */
    public function submit_quote()
    {
        $this->check_vendor_permission();
        $this->load->model('bidding_model');
        $id = $this->input->post('id', true);
        $quote_request = $this->bidding_model->get_quote_request($id);
        if ($this->bidding_model->submit_quote($quote_request)) {
            //send email
            $buyer = get_user($quote_request->buyer_id);
            if (!empty($buyer) && $this->general_settings->send_email_bidding_system == 1) {
                $email_data = array(
                    'email_type' => 'email_general',
                    'to' => $buyer->email,
                    'subject' => trans("quote_request"),
                    'email_content' => trans("your_quote_request_replied") . "<br>" . trans("quote") . ": " . "<strong>#" . $quote_request->id . "</strong>",
                    'email_link' => generate_url("quote_requests"),
                    'email_button_text' => trans("view_details")
                );
                $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
            }
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /*
    *------------------------------------------------------------------------------------------
    * MEMBERSHIP
    *------------------------------------------------------------------------------------------
    */

    /**
     * Payment History
     */
    public function payment_history()
    {
        $this->check_vendor_permission();
        $payment = input_get("payment");
        if ($payment == "membership") {
            if ($this->general_settings->membership_plans_system != 1) {
                redirect(dashboard_url());
                exit();
            }
            $data['title'] = trans("membership_payments");
            $data['description'] = trans("membership_payments") . " - " . $this->app_name;
            $data['keywords'] = trans("membership_payments") . "," . $this->app_name;

            $data['num_rows'] = $this->membership_model->get_membership_transactions_count($this->auth_user->id);
            $pagination = $this->paginate(generate_dash_url("payment_history") . '?payment=membership', $data['num_rows'], $this->per_page);
            $data['transactions'] = $this->membership_model->get_paginated_membership_transactions($this->auth_user->id, $pagination['per_page'], $pagination['offset']);
            $data['lang_settings'] = lang_settings();
            $this->load->view('dashboard/includes/_header', $data);
            $this->load->view('dashboard/payment_history/membership_transactions', $data);
            $this->load->view('dashboard/includes/_footer');
        } elseif ($payment == "promotion") {
            $data['title'] = trans("promotion_payments");
            $data['description'] = trans("promotion_payments") . " - " . $this->app_name;
            $data['keywords'] = trans("promotion_payments") . "," . $this->app_name;

            $data['num_rows'] = $this->promote_model->get_promoted_transactions_count($this->auth_user->id);
            $pagination = $this->paginate(generate_dash_url("payment_history") . '?payment=promotion', $data['num_rows'], $this->per_page);
            $data['transactions'] = $this->promote_model->get_paginated_promoted_transactions($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

            $this->load->view('dashboard/includes/_header', $data);
            $this->load->view('dashboard/payment_history/promotion_transactions', $data);
            $this->load->view('dashboard/includes/_footer');
        } else {
            redirect(dashboard_url());
        }
    }

    /*
    *------------------------------------------------------------------------------------------
    * COMMENTS
    *------------------------------------------------------------------------------------------
    */

    /**
     * Comments
     */
    public function comments()
    {
        $this->check_vendor_permission();
        if ($this->general_settings->product_comments != 1) {
            redirect(dashboard_url());
            exit();
        }
        $data['title'] = trans("comments");
        $data['description'] = trans("comments") . " - " . $this->app_name;
        $data['keywords'] = trans("comments") . "," . $this->app_name;
        $data['lang_settings'] = lang_settings();
        $data['num_rows'] = $this->comment_model->get_vendor_comments_count($this->auth_user->id);
        $pagination = $this->paginate(generate_dash_url("comments"), $data['num_rows'], $this->per_page);
        $data['comments'] = $this->comment_model->get_paginated_vendor_comments($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/comments', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Reviews
     */
    public function reviews()
    {
        $this->check_vendor_permission();
        if ($this->general_settings->reviews != 1) {
            redirect(dashboard_url());
            exit();
        }
        $data['title'] = trans("reviews");
        $data['description'] = trans("reviews") . " - " . $this->app_name;
        $data['keywords'] = trans("reviews") . "," . $this->app_name;
        $data['lang_settings'] = lang_settings();
        $data['num_rows'] = $this->review_model->get_vendor_reviews_count($this->auth_user->id);
        $pagination = $this->paginate(generate_dash_url("reviews"), $data['num_rows'], $this->per_page);
        $data['reviews'] = $this->review_model->get_paginated_vendor_reviews($this->auth_user->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/reviews', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /*
    *------------------------------------------------------------------------------------------
    * SHOP SETTINGS
    *------------------------------------------------------------------------------------------
    */

    /**
     * Shop Settings
     */
    public function shop_settings()
    {
        $this->check_vendor_permission();
        $data['title'] = trans("shop_settings");
        $data['description'] = trans("shop_settings") . " - " . $this->app_name;
        $data['keywords'] = trans("shop_settings") . "," . $this->app_name;

        $data['user_plan'] = $this->membership_model->get_user_plan_by_user_id($this->auth_user->id);
        $data['days_left'] = $this->membership_model->get_user_plan_remaining_days_count($data['user_plan']);
        $data['ads_left'] = $this->membership_model->get_user_plan_remaining_ads_count($data['user_plan']);
        $data['lang_settings'] = lang_settings();
        $data["states"] = array();
        $data["cities"] = array();
        if (!empty($this->auth_user->country_id)) {
            $data["states"] = $this->location_model->get_states_by_country($this->auth_user->country_id);
        }
        if (!empty($this->auth_user->state_id)) {
            $data["cities"] = $this->location_model->get_cities_by_state($this->auth_user->state_id);
        }

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/shop_settings', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Shop Settings Post
     */
    public function shop_settings_post()
    {
        $this->check_vendor_permission();
        $shop_name = remove_special_characters($this->input->post('shop_name', true));
        if (!$this->auth_model->is_unique_shop_name($shop_name, $this->auth_user->id)) {
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            $this->session->set_flashdata('error', trans("msg_shop_name_unique_error"));
            redirect($this->agent->referrer());
            exit();
        }

        if ($this->profile_model->update_shop_settings($shop_name)) {
            $this->session->set_flashdata('success', trans("msg_updated"));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    //show address on map
    public function show_address_on_map()
    {
        $country_text = $this->input->post('country_text', true);
        $country_val = $this->input->post('country_val', true);
        $state_text = $this->input->post('state_text', true);
        $state_val = $this->input->post('state_val', true);
        $address = $this->input->post('address', true);
        $zip_code = $this->input->post('zip_code', true);

        $adress_details = $address . " " . $zip_code;
        $data["map_address"] = "";
        if (!empty($adress_details)) {
            $data["map_address"] = $adress_details . " ";
        }
        if (!empty($state_val)) {
            $data["map_address"] = $data["map_address"] . $state_text . " ";
        }
        if (!empty($country_val)) {
            $data["map_address"] = $data["map_address"] . $country_text;
        }

        $this->load->view('product/_load_map', $data);
    }

    /*
    *------------------------------------------------------------------------------------------
    * SHIPPING SETTINGS
    *------------------------------------------------------------------------------------------
    */

    /**
     * Shipping Settings
     */
    public function shipping_settings()
    {
        $this->check_vendor_permission();
        if (!$this->is_sale_active) {
            redirect(dashboard_url());
            exit();
        }
        $data['title'] = trans("shipping_settings");
        $data['description'] = trans("shipping_settings") . " - " . $this->app_name;
        $data['keywords'] = trans("shipping_settings") . "," . $this->app_name;
        $data['lang_settings'] = lang_settings();
        $data['shipping_zones'] = $this->shipping_model->get_shipping_zones($this->auth_user->id);
        //$data['shipping_classes'] = $this->shipping_model->get_shipping_classes($this->auth_user->id);
        $data['shipping_classes'] = $this->shipping_model->get_default_shipping_classes();
        $data['shipping_default_delivery_times'] = $this->shipping_model->get_default_shipping_delivery_times();
        $data['shipping_delivery_time_ranges'] = $this->shipping_model->get_shipping_delivery_time_ranges();
        $data['shipping_delivery_times'] = $this->shipping_model->get_shipping_delivery_times($this->auth_user->id);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/shipping/shipping_settings', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Add Shipping Zone
     */
    public function add_shipping_zone()
    {
        $this->check_vendor_permission();
        $data['title'] = trans("add_shipping_zone");
        $data['description'] = trans("add_shipping_zone") . " - " . $this->app_name;
        $data['keywords'] = trans("add_shipping_zone") . "," . $this->app_name;
        $data['continents'] = get_continents($this->selected_lang->id);
        //$data['shipping_classes'] = $this->shipping_model->get_active_shipping_classes($this->auth_user->id);
        $data['shipping_classes'] = $this->shipping_model->get_default_shipping_classes();
        $data['shipping_default_delivery_times'] = $this->shipping_model->get_default_shipping_delivery_times();
        $data['shipping_delivery_time_ranges'] = $this->shipping_model->get_shipping_delivery_time_ranges();
        if (!empty($this->default_location->country_id)) {
          $data['default_country'] = $this->default_location->country_id;
        }
        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/shipping/add_shipping_zone2', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Add Shipping Zone Post
     */
    public function add_shipping_zone_post()
    {
        $this->check_vendor_permission();
        if ($this->shipping_model->add_shipping_zone()) {
            $this->session->set_flashdata('success', trans("msg_added"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        //redirect($this->agent->referrer());
        redirect(lang_base_url() . 'dashboard/shipping-settings');
    }

    /**
     * Edit Shipping Zone
     */
    public function edit_shipping_zone($id)
    {
        $this->check_vendor_permission();
        $data['title'] = trans("edit_shipping_zone");
        $data['description'] = trans("edit_shipping_zone") . " - " . $this->app_name;
        $data['keywords'] = trans("edit_shipping_zone") . "," . $this->app_name;

        $data['shipping_zone'] = $this->shipping_model->get_shipping_zone($id);
        if (empty($data['shipping_zone'])) {
            redirect(dashboard_url());
            exit();
        }
        $data['continents'] = get_continents();
        //$data['shipping_classes'] = $this->shipping_model->get_active_shipping_classes($this->auth_user->id);
        $data['shipping_classes'] = $this->shipping_model->get_default_shipping_classes();
        $data['shipping_default_delivery_times'] = $this->shipping_model->get_default_shipping_delivery_times();
        $data['shipping_delivery_time_ranges'] = $this->shipping_model->get_shipping_delivery_time_ranges();
        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/shipping/edit_shipping_zone', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Edit Shipping Zone Post
     */
    public function edit_shipping_zone_post()
    {
        $this->check_vendor_permission();
        $zone_id = $this->input->post('zone_id', true);
        $shipping_zone = $this->shipping_model->get_shipping_zone($zone_id);
        if (empty($shipping_zone)) {
            redirect(generate_dash_url("shipping_settings"));
            exit();
        }
        if ($this->shipping_model->edit_shipping_zone($zone_id)) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_shipping_zone', 1);
        //redirect($this->agent->referrer());
        redirect(lang_base_url() . 'dashboard/shipping-settings');
    }

    /**
     * Delete Shipping Location
     */
    public function delete_shipping_location_post()
    {
        $this->check_vendor_permission();
        $id = $this->input->post('id', true);
        $this->shipping_model->delete_shipping_location($id);
    }

    //select shipping method
    public function select_shipping_method()
    {
        $this->check_vendor_permission();
        $selected_option = $this->input->post('selected_option', true);
        //$shipping_classes = $this->shipping_model->get_active_shipping_classes($this->auth_user->id);
        $shipping_classes = $this->shipping_model->get_default_shipping_classes();;
        $vars = array('selected_option' => $selected_option, 'option_unique_id' => uniqid(), 'shipping_classes' => $shipping_classes);
        $html_content = $this->load->view("dashboard/shipping/_response_shipping_method", $vars, true);
        $data = array(
            'result' => 1,
            'html_content' => $html_content,
        );
        echo json_encode($data);
    }

    /**
     * Add Shipping Class Post
     */
    public function add_shipping_class_post()
    {
        $this->check_vendor_permission();
        if ($this->shipping_model->add_shipping_class()) {
            $this->session->set_flashdata('success', trans("msg_added"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_shipping_class', 1);
        redirect($this->agent->referrer());
    }

    /**
     * Edit Shipping Class Post
     */
    public function edit_shipping_class_post()
    {
        $this->check_vendor_permission();
        $id = $this->input->post('id', true);
        if ($this->shipping_model->edit_shipping_class($id)) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_shipping_class', 1);
        redirect($this->agent->referrer());
    }

    /**
     * Delete Shipping Class
     */
    public function delete_shipping_class_post()
    {
        $this->check_vendor_permission();
        $id = $this->input->post('id', true);
        $this->shipping_model->delete_shipping_class($id);
    }

    /**
     * Add Shipping Delivery Time Post
     */
    public function add_shipping_delivery_time_post()
    {
        $this->check_vendor_permission();
        if ($this->shipping_model->add_shipping_delivery_time()) {
            $this->session->set_flashdata('success', trans("msg_added"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_delivery_time', 1);
        redirect($this->agent->referrer());
    }

    /**
     * Edit Shipping Delivery Time Post
     */
    public function edit_shipping_delivery_time_post()
    {
        $this->check_vendor_permission();
        $id = $this->input->post('id', true);
        if ($this->shipping_model->edit_shipping_delivery_time($id)) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_delivery_time', 1);
        redirect($this->agent->referrer());
    }

    /**
     * Delete Shipping Method
     */
    public function delete_shipping_method_post()
    {
        $this->check_vendor_permission();
        $id = $this->input->post('id', true);
        $this->shipping_model->delete_shipping_method($id);
    }

    /**
     * Delete Shipping Delivery Time
     */
    public function delete_shipping_delivery_time_post()
    {
        $this->check_vendor_permission();
        $id = $this->input->post('id', true);
        $this->shipping_model->delete_shipping_delivery_time($id);
    }

    /**
     * Delete Shipping Zone
     */
    public function delete_shipping_zone_post()
    {
        $this->check_vendor_permission();
        $id = $this->input->post('id', true);
        $this->shipping_model->delete_shipping_zone($id);
    }

    /**
     * Check Vendor Permission
     */
    public function check_vendor_permission($allow_admin = false)
    {
        $has_permission = false;
        if (is_vendor()) {
            $has_permission = true;
        } else {
            if ($allow_admin == true) {
                if (has_permission('products')) {
                    $has_permission = true;
                }
            }
        }
        if ($has_permission == false) {
            if ($this->general_settings->membership_plans_system == 1) {
                redirect(generate_url("start_selling", "select_membership_plan"));
                exit();
            }
            redirect(generate_url("start_selling"));
            exit();
        }
    }

    /**
     * Black list
     */
    public function black_list()
    {
        if (!$this->auth_user->id) {
          redirect('/');
          exit();
        }
        //$this->check_vendor_permission();
        if ($this->general_settings->product_comments != 1) {
            redirect(dashboard_url());
            exit();
        }
        $data['title'] = trans("black_list");
        $data['description'] = trans("black_list") . " - " . $this->app_name;
        $data['keywords'] = trans("black_list") . "," . $this->app_name;
        $data['lang_settings'] = lang_settings();

        //$this->load_model('black_list_model');

        //$data['num_rows'] = $this->comment_model->get_vendor_comments_count($this->auth_user->id);
        //$pagination = $this->paginate(generate_dash_url("comments"), $data['num_rows'], $this->per_page);
        $data['comments'] = $this->black_list_model->get_black_list($this->auth_user->id);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/black_list', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Add Shipping Zone
     */
    public function add_black_list()
    {
        //$this->check_vendor_permission();
        $data['title'] = trans("add_black_list");
        $data['description'] = trans("add_shipping_zone") . " - " . $this->app_name;
        $data['keywords'] = trans("add_shipping_zone") . "," . $this->app_name;
        $data['continents'] = get_continents($this->selected_lang->id);
        $data['shipping_classes'] = $this->shipping_model->get_active_shipping_classes($this->auth_user->id);

        $this->load->view('dashboard/includes/_header', $data);
        $this->load->view('dashboard/add_black_list', $data);
        $this->load->view('dashboard/includes/_footer');
    }

    /**
     * Add Shipping Class Post
     */
    public function add_black_list_post()
    {
      if (!$this->auth_user->id) {
        redirect('/');
        exit();
      }
        //$this->check_vendor_permission();
        if ($this->black_list_model->add_black_list()) {
            $this->session->set_flashdata('success', trans("blacklist_added"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('black_list', 1);
        redirect($this->agent->referrer());
    }

    public function delete_ban_post()
    {
      if (!$this->auth_user->id) {
        redirect('/');
        exit();
      }
        //$this->check_vendor_permission();
        $id = $this->input->post('id', true);
        //echo $id;
        //exit;
        $this->black_list_model->delete_ban($id);
    }

    public function is_cancel_account ()
    {
        $this->db->where('user_id', $this->auth_user->id);
        $res = $this->db->get('cancel_account')->row();
        if ($res) {
            $this->session->set_flashdata('error', trans("cancel_alert"));
            redirect($this->agent->referrer());
            exit();
            redirect('/dashboard');
        }
        return;
    }
}
