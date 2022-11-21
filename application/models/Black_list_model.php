<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Black_list_model extends CI_Model
{

    /*
    *-------------------------------------------------------------------------------------------------
    * PRODUCT COMMENTS
    *-------------------------------------------------------------------------------------------------
    */

    //add black list
    public function add_black_list()
    {
        $username = $this->input->post('username', true);            
        $user = $this->db->where("username", $username)->get('users')->row();
        
        if ($this->auth_check && $user) {
          $data = array(
              'seller_id' => $this->auth_user->id,
              'user_id' => $user->id
          );
            //if (has_permission('comments')) {
                //$data['status'] = 1;
            //}
        } else {        
                return false;
        }        
            $this->db->insert('black_list', $data);
            return true;
      
    }

    //get
    public function get_black_list($seller_id)
    {
        $sql = "SELECT black_list.*, users.slug AS user_slug, users.username 
                FROM black_list LEFT JOIN users ON black_list.user_id = users.id 
                WHERE seller_id = $seller_id ORDER BY id DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //subomments
    public function get_subcomments($parent_id)
    {
        $sql = "SELECT comments.*, users.shop_name AS user_shop_name, users.slug AS user_slug, users.avatar AS user_avatar, users.user_type AS user_type
                FROM comments LEFT JOIN users ON comments.user_id = users.id 
                WHERE parent_id = ? AND status = 1 ORDER BY created_at DESC";
        $query = $this->db->query($sql, array(clean_number($parent_id)));
        return $query->result();
    }

    //comment
    public function get_ban($id)
    {
        $sql = "SELECT * FROM black_list WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    
    public function delete_ban($id)
    {
        $comment = $this->get_ban($id);
        print_r($comment);
        if (!empty($comment)) {

            $this->db->where('id', $id);
            return $this->db->delete('black_list');
        } else {
            return false;
        }
    }
    
    public function check_ban($seller_id, $user_id)
    {
      if ($seller_id && $user_id) {
          $this->db->where('seller_id', $seller_id);
          $this->db->where('user_id', $user_id);
          return $this->db->get('black_list')->num_rows();
      }
      return false;
    }

  


  


}