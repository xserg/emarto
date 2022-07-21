<?php defined('BASEPATH') or exit('No direct script access allowed');

class Coupon_model extends CI_Model
{
    public function input_values()
    {
        $data = array(
            'coupon_code' => remove_special_characters($this->input->post('coupon_code', true)),
            'discount_rate' => $this->input->post('discount_rate', true),
            'coupon_count' => $this->input->post('coupon_count', true),
            'minimum_order_amount' => $this->input->post('minimum_order_amount', true),
            'currency' => $this->default_currency->code,
            'usage_type' => $this->input->post('usage_type', true),
            'category_ids' => "",
            'expiry_date' => $this->input->post('expiry_date', true)
        );
        if ($data["discount_rate"] > 99) {
            $data["discount_rate"] = 99;
        }
        if ($data["discount_rate"] < 1) {
            $data["discount_rate"] = 1;
        }
        if ($data["usage_type"] != 'single' && $data["usage_type"] != 'multiple') {
            $data["usage_type"] = 'single';
        }
        if ($data["coupon_count"] <= 0) {
            $data["discount_rate"] = 0;
        }
        //selected category ids
        $array = array();
        $category_ids = $this->input->post('category_id', true);
        if (!empty($category_ids)) {
            foreach ($category_ids as $id) {
                array_push($array, $id);
            }
            $data['category_ids'] = implode(',', $array);
        }
        return $data;
    }

    //add coupon
    public function add_coupon()
    {
        $data = $this->input_values();
        $data["minimum_order_amount"] = get_price($data["minimum_order_amount"], 'database');
        if (empty($data["minimum_order_amount"])) {
            $data["minimum_order_amount"] = 0;
        }
        $data['seller_id'] = $this->auth_user->id;
        $data['created_at'] = date('Y-m-d H:i:s');

        if ($this->db->insert('coupons', $data)) {
            $coupon_id = $this->db->insert_id();
            //coupon products
            $product_ids = $this->get_selected_products_array();
            foreach ($product_ids as $item) {
                if (empty($this->db->where('coupon_id', clean_number($coupon_id))->where('product_id', clean_number($item))->get('coupon_products')->row())) {
                    $this->db->insert('coupon_products', ['coupon_id' => clean_number($coupon_id), 'product_id' => clean_number($item)]);
                }
            }
        }
        return true;
    }

    //edit coupon
    public function edit_coupon($id)
    {
        $data = $this->input_values();
        $data["minimum_order_amount"] = get_price($data["minimum_order_amount"], 'database');
        if (empty($data["minimum_order_amount"])) {
            $data["minimum_order_amount"] = 0;
        }
        if ($this->db->where('id', clean_number($id))->update('coupons', $data)) {
            //coupon products
            $product_ids = $this->get_selected_products_array();
            $coupon_products = $this->get_coupon_products($id);
            if (!empty($coupon_products)) {
                foreach ($coupon_products as $item) {
                    if (!in_array($item->product_id, $product_ids)) {
                        $this->db->where('coupon_id', clean_number($id))->where('product_id', clean_number($item->product_id))->delete('coupon_products');
                    }
                }
            }
            if (!empty($product_ids)) {
                foreach ($product_ids as $product_id) {
                    if (empty($this->db->where('coupon_id', clean_number($id))->where('product_id', clean_number($product_id))->get('coupon_products')->row())) {
                        $this->db->insert('coupon_products', ['coupon_id' => clean_number($id), 'product_id' => clean_number($product_id)]);
                    }
                }
            }
        }
        return true;
    }

    //add used coupon
    public function add_used_coupon($order_id, $coupon_code)
    {
        $user_id = 0;
        if ($this->auth_check) {
            $user_id = $this->auth_user->id;
        }
        $data = [
            'order_id' => $order_id,
            'user_id' => $user_id,
            'coupon_code' => $coupon_code,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('coupons_used ', $data);
    }

    //get selected products array
    public function get_selected_products_array()
    {
        $array = array();
        $product_ids = $this->input->post('product_id', true);
        if(!empty($product_ids)){
            foreach ($product_ids as $key => $value) {
                array_push($array, $value);
            }
        }
        return $array;
    }

    //get coupon
    public function get_coupon($id)
    {
        return $this->db->where('id', clean_number($id))->get('coupons')->row();
    }

    //get coupon by code
    public function get_coupon_by_code($code)
    {
        return $this->db->where('coupon_code', remove_special_characters($code))->limit(1)->get('coupons')->row();
    }

    //get coupon by code cart
    public function get_coupon_by_code_cart($code)
    {
        return $this->db->select('coupons.seller_id, coupons.coupon_code, coupons.discount_rate, coupons.coupon_count, coupons.minimum_order_amount, coupons.currency, coupons.usage_type, coupons.expiry_date,
        (SELECT GROUP_CONCAT(coupon_products.product_id) FROM coupon_products WHERE coupon_products.coupon_id = coupons.id) AS product_ids, 
        (SELECT COUNT(coupons_used.id) FROM coupons_used WHERE coupons_used.coupon_code = coupons.coupon_code) AS used_coupon_count')->where('coupon_code', remove_special_characters($code))->limit(1)->get('coupons')->row();
    }

    //get coupons paginated
    public function get_coupons_paginated($user_id, $per_page, $offset)
    {
        return $this->db->where('seller_id', clean_number($user_id))->order_by('created_at', 'DESC')->limit($per_page, $offset)->get('coupons')->result();
    }

    //get coupons count
    public function get_coupons_count($user_id)
    {
        return $this->db->where('seller_id', clean_number($user_id))->count_all_results('coupons');
    }

    //get used coupons count
    public function get_used_coupons_count($coupon_code)
    {
        return $this->db->where('coupon_code', remove_special_characters($coupon_code))->count_all_results('coupons_used');
    }

    //check coupon used
    public function check_coupon_used($user_id, $coupon_code)
    {
        return $this->db->where('coupon_code', remove_special_characters($coupon_code))->where('user_id', clean_number($user_id))->count_all_results('coupons_used');
    }

    //get coupon products by category
    public function get_coupon_products_by_category($user_id, $category_id)
    {
        $this->db->select('products.*, product_details.title')->join('product_details', 'product_details.product_id = products.id');
        $this->db->where('product_details.lang_id', clean_number($this->selected_lang->id));
        $this->db->where('products.user_id', clean_number($user_id))->where('products.category_id', clean_number($category_id))->where('products.listing_type', "sell_on_site");
        $this->db->where('products.status', 1)->where('products.visibility', 1)->where('products.is_draft', 0)->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        return $this->db->get('products')->result();
    }

    //get coupon products
    public function get_coupon_products($coupon_id)
    {
        return $this->db->where('coupon_id', clean_number($coupon_id))->get('coupon_products')->result();
    }

    //get coupon products ids array
    public function get_coupon_products_ids_array($coupon_id)
    {
        $array = array();
        $products = $this->get_coupon_products($coupon_id);
        if (!empty($products)) {
            foreach ($products as $product) {
                array_push($array, $product->id);
            }
        }
        return $array;
    }

    //delete coupon
    public function delete_coupon($coupon)
    {
        if (!empty($coupon)) {
            if ($this->db->where('id', $coupon->id)->delete('coupons')) {
                $this->db->where('coupon_id', $coupon->id)->delete('coupon_products');
                $this->db->where('coupon_code', $coupon->coupon_code)->delete('coupons_used');
                return true;
            }
        }
        return false;
    }
}
