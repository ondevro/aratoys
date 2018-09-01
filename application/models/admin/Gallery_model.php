<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends CI_Model
{
    public function getGallery(){
        return $this->db->get('gallery')->result();
    }

    public function save($param){
        $data = array(
            'link' => $param['link']
        );

        $this->db->insert('gallery', $data);

        return $this->db->insert_id();
    }
}