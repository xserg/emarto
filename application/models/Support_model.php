<?php defined('BASEPATH') or exit('No direct script access allowed');

class Support_model extends CI_Model
{
    /*
    *-------------------------------------------------------------------------------------------------
    * CONTENT
    *-------------------------------------------------------------------------------------------------
    */

    //input values
    public function input_values()
    {
        return [
            'lang_id' => $this->input->post('lang_id', true),
            'title' => $this->input->post('title', true),
            'slug' => $this->input->post('slug', true),
            'content' => $this->input->post('content', false),
            'category_id' => $this->input->post('category_id', true),
            'content_order' => $this->input->post('content_order', true)
        ];
    }

    //add content
    public function add_content()
    {
        $data = $this->input_values();
        if (empty($data["slug"])) {
            $data["slug"] = str_slug($data["title"]);
        }
        $data["created_at"] = date('Y-m-d H:i:s');
        return $this->db->insert('knowledge_base', $data);
    }

    //edit content
    public function edit_content($id)
    {
        $content = $this->get_content($id);
        if (!empty($content)) {
            $data = $this->input_values();
            $data['slug'] = remove_special_characters($data['slug'], true);
            if (empty($data['slug'])) {
                $data["slug"] = str_slug($data["title"]);
            }
            return $this->db->where('id', $id)->update('knowledge_base', $data);
        }
        return false;
    }

    //get content
    public function get_content($id)
    {
        return $this->db->where('id', clean_number($id))->get('knowledge_base')->row();
    }

    //get content by slug
    public function get_content_by_slug($slug)
    {
        return $this->db->where('slug', clean_slug($slug))->get('knowledge_base')->row();
    }

    //get contents
    public function get_contents()
    {
        return $this->db->get('knowledge_base')->result();
    }

    //get contents by category
    public function get_contents_by_category($category_id)
    {
        return $this->db->where('category_id', clean_number($category_id))->order_by('knowledge_base.content_order')->get('knowledge_base')->result();
    }

    //get first content by category
    public function get_first_content_by_category($category_id)
    {
        return $this->db->where('category_id', clean_number($category_id))->limit(1)->get('knowledge_base')->row();
    }

    //get contents by langugae
    public function get_contents_by_lang($lang_id)
    {
        $this->db->select('knowledge_base.*, (SELECT name FROM knowledge_base_categories WHERE knowledge_base.category_id = knowledge_base_categories.id) AS category_name');
        return $this->db->where('lang_id', clean_number($lang_id))->order_by('knowledge_base.content_order')->get('knowledge_base')->result();
    }

    //get content search count
    public function get_content_search_count($lang_id, $q)
    {
        $this->db->select('knowledge_base.*, (SELECT name FROM knowledge_base_categories WHERE knowledge_base.category_id = knowledge_base_categories.id) AS category_name, 
        (SELECT slug FROM knowledge_base_categories WHERE knowledge_base.category_id = knowledge_base_categories.id) AS category_slug');
        if (!empty($q)) {
            $this->db->like('knowledge_base.title', clean_str($q))->or_like('knowledge_base.content', clean_str($q));
        }
        return $this->db->where('lang_id', clean_number($lang_id))->count_all_results('knowledge_base');
    }

    //get content search results
    public function get_content_search_results($lang_id, $q, $per_page, $offset)
    {
        $this->db->select('knowledge_base.*, (SELECT name FROM knowledge_base_categories WHERE knowledge_base.category_id = knowledge_base_categories.id) AS category_name, 
        (SELECT slug FROM knowledge_base_categories WHERE knowledge_base.category_id = knowledge_base_categories.id) AS category_slug');
        if (!empty($q)) {
            $this->db->like('knowledge_base.title', clean_str($q))->or_like('knowledge_base.content', clean_str($q));
        }
        return $this->db->where('lang_id', clean_number($lang_id))->order_by('knowledge_base.created_at', 'DESC')->limit($per_page, $offset)->get('knowledge_base')->result();
    }

    //delete content
    public function delete_content($id)
    {
        $content = $this->get_content($id);
        if (!empty($content)) {
            return $this->db->where('id', $content->id)->delete('knowledge_base');
        }
        return false;
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * CATEGORY
    *-------------------------------------------------------------------------------------------------
    */

    //add category
    public function add_category()
    {
        $data = [
            'lang_id' => $this->input->post('lang_id', true),
            'name' => $this->input->post('name', true),
            'slug' => $this->input->post('slug', true),
            'category_order' => $this->input->post('category_order', true)
        ];

        if (empty($data["slug"])) {
            $data["slug"] = str_slug($data["name"]);
        }
        return $this->db->insert('knowledge_base_categories', $data);
    }

    //edit category
    public function edit_category($id)
    {
        $category = $this->get_category($id);
        if (!empty($category)) {
            $data = [
                'lang_id' => $this->input->post('lang_id', true),
                'name' => $this->input->post('name', true),
                'slug' => $this->input->post('slug', true),
                'category_order' => $this->input->post('category_order', true)
            ];
            $data['slug'] = remove_special_characters($data['slug'], true);
            return $this->db->where('id', $id)->update('knowledge_base_categories', $data);
        }
        return false;
    }

    //get category
    public function get_category($id)
    {
        return $this->db->where('id', clean_number($id))->get('knowledge_base_categories')->row();
    }

    //get category by slug
    public function get_category_by_slug($slug)
    {
        return $this->db->where('slug', clean_slug($slug))->get('knowledge_base_categories')->row();
    }

    //get categories
    public function get_categories()
    {
        return $this->db->get('knowledge_base_categories')->result();
    }

    //get categories by langugae
    public function get_categories_by_lang($lang_id)
    {
        return $this->db->select('knowledge_base_categories.*, (SELECT COUNT(knowledge_base.id) FROM knowledge_base WHERE knowledge_base.category_id = knowledge_base_categories.id) AS num_content')->where('lang_id', clean_number($lang_id))->order_by('category_order')->get('knowledge_base_categories')->result();
    }

    //delete category
    public function delete_category($id)
    {
        $category = $this->get_category($id);
        if (!empty($category)) {
            return $this->db->where('id', $category->id)->delete('knowledge_base_categories');
        }
        return false;
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * TICKET
    *-------------------------------------------------------------------------------------------------
    */

    /*
     * Status
     * 1: Open
     * 2: Responded
     * 3: Closed
     */

    //ticket input values
    public function input_values_ticket()
    {
        return [
            'subject' => $this->input->post('subject', true),
            'message' => $this->input->post('message', false)
        ];
    }

    //add ticket
    public function add_ticket($is_support_reply)
    {
        $inputs = $this->input_values_ticket();
        $user_id = 0;
        $is_guest = 1;
        if ($this->auth_check) {
            $user_id = user()->id;
            $is_guest = 0;
        }
        $data = [
            'user_id' => $user_id,
            'name' => '',
            'email' => '',
            'subject' => $inputs['subject'],
            'is_guest' => $is_guest,
            'status' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        if ($is_guest == 1) {
            $data['name'] = $this->input->post('name', true);
            $data['email'] = $this->input->post('email', true);
        }
        if ($this->db->insert('support_tickets ', $data)) {
            $id = $this->db->insert_id();
            return $this->add_subticket($id, $is_support_reply, $user_id);
        }
        return false;
    }

    //add ticket
    public function add_subticket($ticket_id, $is_support_reply, $user_id = null)
    {
        if ($user_id == null) {
            $user_id = 0;
            if(auth_check()){
                $user_id = user()->id;
            }
        }
        $data = [
            'ticket_id' => $ticket_id,
            'user_id' => $user_id,
            'message' => $this->input->post('message', false),
            'attachments' => '',
            'storage' => 'local',
            'is_support_reply' => $is_support_reply,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $arrayFiles = array();
        if (!empty($this->session->userdata('ticket_attachments'))) {
            $filesSession = $this->session->userdata('ticket_attachments');
            foreach ($filesSession as $item) {
                $ext = "";
                $new_name = $item->name;
                if (!empty($item->name)) {
                    $ext = pathinfo($item->name, PATHINFO_EXTENSION);
                }
                $new_name = "attachment_" . uniqid() . "." . $ext;

                $itemFile = new stdClass();
                $itemFile->id = $item->uniqid;
                $itemFile->orj_name = $item->name;
                $itemFile->name = $new_name;

                $new_path = "uploads/support/" . $new_name;
                //move to s3
                if ($this->storage_settings->storage == "aws_s3") {
                    $this->load->model("aws_model");
                    $data["storage"] = "aws_s3";
                    //move files
                    if (!empty($item->temp_path)) {
                        $this->aws_model->put_support_object($new_path, $item->temp_path);
                        delete_file_from_server($item->temp_path);
                    }
                } else {
                    @copy($item->temp_path, FCPATH . $new_path);
                    @unlink($item->temp_path);
                }
                array_push($arrayFiles, $itemFile);
            }
        }
        if (!empty($arrayFiles)) {
            $data['attachments'] = serialize($arrayFiles);
        }
        if ($this->db->insert('support_subtickets ', $data)) {
            if ($is_support_reply == 1) {
                $this->db->where('id', clean_number($ticket_id))->update('support_tickets ', ['status' => 2]);
            } else {
                $this->db->where('id', clean_number($ticket_id))->update('support_tickets ', ['status' => 1]);
            }
            $this->session->unset_userdata('ticket_attachments');
        }
        return true;
    }

    //get ticket
    public function get_ticket($id)
    {
        return $this->db->where('id', clean_number($id))->get('support_tickets')->row();
    }

    //get tickets count
    public function get_tickets_count($status)
    {
        if ($status == 1 || $status == 2 || $status == 3) {
            $this->db->where('status', clean_number($status));
        }
        return $this->db->count_all_results('support_tickets');
    }

    //get tickets paginated
    public function get_tickets_paginated($status, $per_page, $offset)
    {
        if ($status == 1 || $status == 2 || $status == 3) {
            $this->db->where('status', clean_number($status));
        }
        $this->db->order_by('created_at', 'DESC')->limit(clean_number($per_page), clean_number($offset));
        return $this->db->get('support_tickets')->result();
    }

    //get tickets by user
    public function get_tickets_by_user($user_id)
    {
        return $this->db->where('user_id', clean_number($user_id))->order_by('status, id DESC')->get('support_tickets')->result();
    }

    //get subticket
    public function get_subticket($id)
    {
        return $this->db->where('id', clean_number($id))->get('support_subtickets')->result();
    }

    //get subtickets
    public function get_subtickets($ticket_id)
    {
        return $this->db->where('ticket_id', clean_number($ticket_id))->order_by('id DESC')->get('support_subtickets')->result();
    }

    //change ticket status
    public function change_ticket_status($id, $status)
    {
        if (is_admin() && ($status == 1 || $status == 2 || $status == 3)) {
            return $this->db->where('id', clean_number($id))->update('support_tickets', ['status' => clean_number($status)]);
        }
        return false;
    }

    //close ticket
    public function close_ticket($id)
    {
        $ticket = $this->get_ticket($id);
        if (!empty($ticket)) {
            if (user()->id == $ticket->user_id) {
                return $this->db->where('id', $ticket->id)->update('support_tickets', ['status' => 3]);
            }
        }
        return false;
    }

    //delete ticket
    public function delete_ticket($id)
    {
        $ticket = $this->get_ticket($id);
        if (!empty($ticket)) {
            return $this->db->where('id', $ticket->id)->delete('support_tickets');
        }
        return false;
    }

}