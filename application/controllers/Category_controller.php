<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Categories
     */
    public function categories()
    {
        check_permission('categories');

        $this->category_model->check_category_parent_trees();
        $data['title'] = trans("categories");
        $data["lang"] = $this->input->get("lang", true);
        if (empty($data["lang"])) {
            $data["lang"] = $this->selected_lang->id;
        }
        if (!check_language_exist($data["lang"])) {
            $data["lang"] = $this->selected_lang->id;
            redirect(admin_url() . "categories?lang=" . $this->selected_lang->id);
            exit();
        }
        $data['parent_categories'] = $this->category_model->get_all_parent_categories_by_lang(clean_number($data["lang"]));

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/categories', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Category
     */
    public function add_category()
    {
        check_permission('categories');
        $data['title'] = trans("add_category");
        $data['parent_categories'] = $this->category_model->get_all_parent_categories();
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/add_category', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Category Post
     */
    public function add_category_post()
    {
        check_permission('categories');
        if ($this->category_model->add_category()) {
            reset_cache_data($this, "st");
            $this->session->set_flashdata('success_form', trans("msg_category_added"));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('form_data', $this->category_model->input_values());
            $this->session->set_flashdata('error_form', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Update Category
     */
    public function update_category($id)
    {
        check_permission('categories');
        $data['title'] = trans("update_category");
        //get category
        $data['category'] = $this->category_model->get_category_back_end($id);
        if (empty($data['category'])) {
            redirect($this->agent->referrer());
            exit();
        }

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/update_category', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Update Category Post
     */
    public function update_category_post()
    {
        check_permission('categories');
        $id = $this->input->post('id', true);
        if ($this->category_model->update_category($id)) {
            reset_cache_data($this, "st");
            $this->session->set_flashdata('success', trans("msg_updated"));
            redirect(admin_url() . 'categories');
        } else {
            $this->session->set_flashdata('form_data', $this->category_model->input_values());
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Bulk Category Upload
     */
    public function bulk_category_upload()
    {
        check_permission('categories');
        $data['title'] = trans("bulk_category_upload");

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/bulk_category_upload', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Download CSV Files Post
     */
    public function download_csv_files_post()
    {
        check_permission('categories');
        post_method();
        $submit = $this->input->post('submit', true);
        if ($submit == 'csv_template') {
            $this->load->helper('download');
            force_download(FCPATH . "assets/file/csv_category_template.csv", NULL);
        } elseif ($submit == 'csv_example') {
            $this->load->helper('download');
            force_download(FCPATH . "assets/file/csv_category_example.csv", NULL);
        }
    }

    /**
     * Generate CSV Object Post
     */
    public function generate_csv_object_post()
    {
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
            $csv_object = $this->category_model->generate_csv_object($file_path);
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
        $txt_file_name = $this->input->post('txt_file_name', true);
        $index = $this->input->post('index', true);

        $name = $this->category_model->import_csv_item($txt_file_name, $index);
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

        reset_cache_data($this, "st");
    }

    /**
     * Category Settings Post
     */
    public function category_settings_post()
    {
        check_permission('categories');
        if ($this->category_model->update_settings()) {
            reset_cache_data($this, "st");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error_form', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_settings', 1);
        redirect($this->agent->referrer());
    }

    /**
     * Delete Category Post
     */
    public function delete_category_post()
    {
        check_permission('categories');
        $id = $this->input->post('id', true);
        //check subcategories
        if (!empty($this->category_model->get_subcategories_by_parent_id($id))) {
            $this->session->set_flashdata('error', trans("msg_delete_subcategories"));
        } else {
            if ($this->category_model->delete_category($id)) {
                reset_cache_data($this, "st");
                $this->session->set_flashdata('success', trans("msg_category_deleted"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }
        }
    }

    //get categories by language
    public function get_categories_by_lang()
    {
        $lang_id = $this->input->post('lang_id', true);
        if (!empty($lang_id)):
            $categories = $this->category_model->get_categories_by_lang($lang_id);
            foreach ($categories as $item) {
                echo '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        endif;
    }

    //update featured categories order
    public function update_featured_categories_order_post()
    {
        check_permission('categories');
        $this->category_model->update_featured_categories_order();
        reset_cache_data($this, "st");
    }

    //update index categories order
    public function update_index_categories_order_post()
    {
        check_permission('categories');
        $this->category_model->update_index_categories_order();
        reset_cache_data($this, "st");
    }

    //load categories
    public function load_categories()
    {
        $vars = array(
            "parent_category_id" => $this->input->post('id', true),
            "lang_id" => $this->input->post('lang_id', true)
        );
        $html_content = $this->load->view('admin/category/print_categories', $vars, true);
        $data = array(
            'result' => 1,
            'html_content' => $html_content,
        );
        echo json_encode($data);
    }

    //delete category image
    public function delete_category_image_post()
    {
        check_permission('categories');
        $category_id = $this->input->post('category_id', true);
        $this->category_model->delete_category_image($category_id);
        reset_cache_data($this, "st");
    }


    /*
    *-------------------------------------------------------------------------------------------------
    * CUSTOM FIELDS
    *-------------------------------------------------------------------------------------------------
    */

    /**
     * Add Custom Field
     */
    public function add_custom_field()
    {
        check_permission('custom_fields');
        $data['title'] = trans("add_custom_field");
        $data['categories'] = $this->category_model->get_all_parent_categories();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/add_custom_field', $data);
        $this->load->view('admin/includes/_footer');
    }


    /**
     * Add Custom Field Post
     */
    public function add_custom_field_post()
    {
        check_permission('custom_fields');
        if ($this->field_model->add_field()) {
            //last id
            $last_id = $this->db->insert_id();
            reset_cache_data($this, "st");
            redirect(admin_url() . 'custom-field-options/' . $last_id);
        } else {
            $this->session->set_flashdata('form_data', $this->field_model->input_values());
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }


    /**
     * Update Custom Field
     */
    public function update_custom_field($id)
    {
        check_permission('custom_fields');
        $data['title'] = trans("update_custom_field");
        //get field
        $data['field'] = $this->field_model->get_field($id);
        if (empty($data['field'])) {
            redirect(admin_url() . "custom-fields");
        }
        $data['categories'] = $this->category_model->get_all_parent_categories();
        $data['field_categories'] = $this->field_model->get_field_categories($data['field']->id);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/update_custom_field', $data);
        $this->load->view('admin/includes/_footer');
    }


    /**
     * Update Custom Field Post
     */
    public function update_custom_field_post()
    {
        check_permission('custom_fields');
        $id = $this->input->post('id', true);
        if ($this->field_model->update_field($id)) {
            reset_cache_data($this, "st");
            $this->session->set_flashdata('success', trans("msg_updated"));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }


    /**
     * Custom Fields
     */
    public function custom_fields()
    {
        check_permission('custom_fields');
        $data['title'] = trans("custom_fields");
        $data['fields'] = $this->field_model->get_fields();
        $data['admin_settings'] = $this->product_admin_model->get_admin_settings();
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/custom_fields', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Delete Custom Field Post
     */
    public function delete_custom_field_post()
    {
        check_permission('custom_fields');
        $id = $this->input->post('id', true);
        if ($this->field_model->delete_field($id)) {
            reset_cache_data($this, "st");
            $this->session->set_flashdata('success', trans("msg_custom_field_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Add Remove Custom Fields Filters
     */
    public function add_remove_custom_field_filters_post()
    {
        check_permission('custom_fields');
        $id = $this->input->post('id', true);
        if ($this->field_model->add_remove_custom_field_filters($id)) {
            reset_cache_data($this, "st");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Custom Field Options
     */
    public function custom_field_options($id)
    {
        check_permission('custom_fields');
        $data['title'] = trans("add_custom_field");
        //get field
        $data['field'] = $this->field_model->get_field($id);

        if (empty($data['field'])) {
            redirect(admin_url() . 'custom-fields');
        }
        $data['parent_categories'] = $this->category_model->get_all_parent_categories();
        $data['options'] = $this->field_model->get_field_all_options($id);
        $data['field_categories'] = $this->field_model->get_field_categories($id);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/category/custom_field_options', $data);
        $this->load->view('admin/includes/_footer');
    }

    //add custom field optiom
    public function add_custom_field_option_post()
    {
        check_permission('custom_fields');
        $field_id = $this->input->post("field_id");
        $this->field_model->add_field_option($field_id);
        reset_cache_data($this, "st");
        redirect($this->agent->referrer());
    }

    /**
     * Update Custom Field Option Post
     */
    public function update_custom_field_option_post()
    {
        check_permission('custom_fields');
        $this->field_model->update_field_option();
        reset_cache_data($this, "st");
        redirect($this->agent->referrer());
    }

    //delete custom field optiom
    public function delete_custom_field_option()
    {
        check_permission('custom_fields');
        $id = $this->input->post("id");
        $this->field_model->delete_custom_field_option($id);
        reset_cache_data($this, "st");
    }

    //add category to custom field
    public function add_category_to_custom_field()
    {
        check_permission('custom_fields');
        $this->field_model->add_category_to_field();
        reset_cache_data($this, "st");
        redirect($this->agent->referrer());
    }

    /**
     * Custom Field Settings Post
     */
    public function custom_field_settings_post()
    {
        check_permission('custom_fields');
        if ($this->field_model->update_field_options_settings()) {
            reset_cache_data($this, "st");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_settings', 1);
        redirect($this->agent->referrer());
    }

    //delete category from a custom field
    public function delete_custom_field_category()
    {
        check_permission('custom_fields');
        $field_id = $this->input->post("field_id");
        $category_id = $this->input->post("category_id");
        $this->field_model->delete_category_from_field($field_id, $category_id);
        reset_cache_data($this, "st");
        redirect($this->agent->referrer());
    }
}
