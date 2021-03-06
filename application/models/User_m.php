<?php

class User_m extends MY_Model
{
    protected $_table_name = 'users';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = '';
    protected $_order = 'desc';
    protected $_timestamps = TRUE;

    public function __construct()
    {
        parent::__construct();
    }

    public function validation_rules($id = NULL)
    {
        $rules = array(
            array(
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'numeric|required',
            ),
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|min_length[6]',
            ),
            array(
                'field' => 'password_confirmation',
                'label' => 'Password Confirmation',
                'rules' => 'trim|matches[password]',
            ),
            array(
                'field' => 'active',
                'label' => 'Active',
                'rules' => 'numeric|required',
            ),
        );

        if ($id) {
            $rules[] = array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required',
            );
        } else {
            $rules[] = array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|is_unique[users.username]',
            );
        }

        return $rules;
    }

    public function get_with_paginate($page = 1, $per_page = 10)
    {
        $total_rows = $this->db->get($this->_table_name)->num_rows();
        $total_pages = ceil($total_rows / $per_page);
        $offset = ($page * $per_page) - $per_page;

        $data = $this->db
            ->select("u1.id as id, u1.name as name, u1.username as username, r.name as role, u2.name as updated_by, u1.is_active as is_active")
            ->join("$this->_table_name u2", "u1.updated_by = u2.id", 'left')
            ->join('roles r', 'u1.role_id = r.id', 'left')
            ->order_by('id', 'DESC')
            ->get("$this->_table_name u1", $per_page, $offset)
            ->result();

        return array(
            'total_pages' => $total_pages,
            'current_page' => $page,
            'prev_page' => ($page - 1) > 0 ? $page - 1 : NULL,
            'next_page' => ($page + 1) <= $total_pages ? $page + 1 : NULL,
            'data' => $data,
        );
    }

    public function profile_validation_rules()
    {
        return array(
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'old_password',
                'label' => 'Old Password',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'new_password',
                'label' => 'New Password',
                'rules' => 'trim|required|min_length[6]',
            ),
            array(
                'field' => 'password_confirmation',
                'label' => 'Password Confirmation',
                'rules' => 'trim|matches[new_password]',
            ),
        );
    }
}
