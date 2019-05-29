<?php

class Video extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Video_m');
    }

    public function index()
    {
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $this->data['videos'] = $this->Video_m->get_with_paginate($page);
        $this->data['page'] = 'admin/video/index';
        $this->load->view('admin/_layout', $this->data);
    }

    public function form($id = NULL)
    {
        $this->data['page'] = 'admin/video/form';
        if ($id === NULL) {
            $this->data['title'] = 'create new video';
        } else {
            $this->data['title'] = 'update video';
            $this->data['video'] = $this->Video_m->get($id, TRUE);
        }
        $this->form_validation->set_rules($this->Video_m->validation_rules($id));
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/_layout', $this->data);
        } else {
            $data = array(
                'title' => htmlspecialchars($this->input->post('title', TRUE)),
                'link' => htmlspecialchars($this->input->post('link', TRUE)),
                'slug' => url_title(strtolower($this->input->post('title', TRUE))),
                'is_publish' => htmlspecialchars($this->input->post('is_publish', TRUE)),
                'updated_by' => $this->session->userdata('user')['id'],
            );
            if ($id === NULL) {
                $this->Video_m->save($data);
            } else {
                $this->Video_m->save($data, $id);
            }
            $this->session->set_flashdata('form_status', array(
                'status' => 'success',
                'message' => 'Successfully ' . $this->data['title'] . '!',
            ));
            redirect('admin/video');
        }
    }

    public function change_publish($id)
    {
        $video = $this->Video_m->get($id);
        $data = array(
            'is_publish' => $video->is_publish ? 0 : 1,
        );
        $this->Video_m->save($data, $id);
        $this->session->set_flashdata('form_status', array(
            'status' => 'success',
            'message' => 'Successfully change publish option!',
        ));
        redirect('admin/video');
    }

    public function delete($id)
    {
        $this->Video_m->delete($id);
        $this->session->set_flashdata('form_status', array(
            'status' => 'success',
            'message' => 'Successfully delete video!',
        ));
        redirect('admin/video');
    }
}
