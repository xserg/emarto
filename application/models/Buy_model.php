<?php defined('BASEPATH') or exit('No direct script access allowed');

class Buy_model extends CI_Model
{
    public function input_values()
    {
        $data = array(
            'description' => remove_special_characters($this->input->post('description', true)),
            'price' => $this->input->post('price', true),
            'title' => $this->input->post('title', true),
            'currency' => $this->input->post('currency', true),
            'country_id' => $this->input->post('country_id', true),
            //'category_id' => $this->input->post('category_id', true),,
            
        );
        //return $data;
        //selected category ids
        $array = array();
        $category_ids = $this->input->post('category_id', true);
        
        //print_r($category_ids);
        //exit;
        if (!empty($category_ids)) {
            foreach ($category_ids as $id) {
                array_push($array, $id);
                if ($id) {
                    $data['category_id'] = $id;
                }
            }
            //$data['category_id'] = implode(',', $array);
        }
        return $data;
    }

    //add coupon
    public function add_buy_request()
    {
        $data = $this->input_values();
        
        $data['user_id'] = $this->auth_user->id;
        $data['created_at'] = date('Y-m-d H:i:s');

        if ($this->db->insert('buy_request', $data)) {
          return $this->db->insert_id();
        }
        return false;
    }

    //get refund requests
    public function get_buy_request($id)
    {
        $this->db->select('buy_request.*,users.username AS user_username, users.slug AS user_slug');
        $this->db->join('users', 'buy_request.user_id = users.id');
        return $this->db->where('buy_request.id', clean_number($id))->get('buy_request')->row();
    }

    //get refund requests count
    public function get_buy_requests_count($user_id)
    {
        $this->db->where('user_id', clean_number($user_id));  
        return $this->db->count_all_results('buy_request');
    }

    //get paginated orders
    public function get_buy_requests_paginated($user_id, $per_page, $offset)
    {
        $this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        $this->db->select('buy_request.*, buy_images.image_path_thumb');
        if ($user_id) {
            $this->db->where('buy_request.user_id', clean_number($user_id));
        }
        $this->db->join('buy_images', 'buy_request.id=buy_images.message_id', 'left');
        $this->db->group_by('buy_request.id');
        return $this->db->order_by('created_at', 'DESC')->limit($per_page, $offset)->get('buy_request')->result();
    }
    
    public function delete_buy_request($id)
    {
  
            $this->db->where('id', $id);
            $this->db->delete('buy_request');
  
    }
}
