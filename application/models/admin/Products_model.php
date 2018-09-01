<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Products_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_record($table = null, $params = null, $data = '*') {
        $this->db->select($data);

        return $this->db->get_where($table, $params)->row_array();
    }

    public function get_records($table = null, $params = null, $data = '*', $orderby = null) {
        $this->db->select($data);

        if($orderby) {
            $this->db->order_by($orderby);
        }

        return $this->db->get_where($table, $params)->result('array');
    }

    public function get_count_record($table, $params = null) {
        if(isset($params)) {
            $this->db->where($params);
        }

        $this->db->from($table);

        return $this->db->count_all_results();
    }
/*
    public function get_all_products($params = array()) {
        $this->db->order_by('id', 'desc');

        if(isset($params) && !empty($params))
        {
            $this->db->limit($params['limit'], $params['offset']);
        }

        return $this->db->get('products')->result_array();
    }*/
    public function get_all_products($params = null) {
        $this->db->select('products.*, brand.name as brand_name, categories.name as category_name, gallery.link as images')->from('products')->join('brand', 'products.brand_id = brand.id')->join('categories', 'products.category_id = categories.id')->join('gallery', 'products.id = gallery.product_id')->group_by('products.id')->order_by('name', 'asc');

        if(isset($params['per_page'])) {
            $this->db->limit($params['per_page'], $params['page']);
        }

        if(isset($params['search'])) {
            $this->db->or_like(array('code' => $params['search'], 'products.name' => $params['search']));
        }

        return $this->db->get()->result_array();
    }

    public function get_attributes($product_id) {
        $this->db->select('at.name AS type, at.id as type_id, av.name AS value')->from('attribute_value av')->join('attribute_type at', 'av.type_id = at.id')->where('av.id IN (SELECT attribute_id FROM attribute_to_product WHERE product_id = ' . $product_id . ')');

        return $this->db->get()->result_array();
    }

    public function new_record($table, $data) {
        $this->db->insert($table, $data);

        return $this->db->insert_id();
    }

    public function update_record($table = null, $params = null, $data = null) {
        $this->db->where($params)->update($table, $data);

        return $this->db->affected_rows();
    }

    public function delete_record($table = null, $data = null) {
        return $this->db->delete($table, $data);
    }

    public function get_record_id_by($table = null, $params = null, $data = null) {
        $find_record = $this->db->select('id')->get_where($table, $params);

        if($find_record->num_rows() > 0) {
            $record_id = $find_record->result_array()[0]['id'];
        } else {
            $record_id = $this->new_record($table, $data);
        }

        return $record_id;
    }

    public function get_record_data($table = null, $params = null, $data = null, $select = 'id') {
        $find_record = $this->db->select($select)->get_where($table, $params);

        if($find_record->num_rows() > 0) {
            $record_id = $find_record->result_array()[0][$select];
        } else {
            $record_id = $this->new_record($table, $data);
        }

        return $record_id;
    }
/*
    public function get_brand_id_by_name($name = null) {
        $find_brand = $this->db->select('id')->get_where('brand', array('name' => $name));

        if($find_brand->num_rows() > 0) {
            $brand_id = $find_brand->result_array()[0]['id'];
        } else {
            $brand_id = $this->new_record('brand', array('name' => $name));
        }

        return $brand_id;
    }

    public function get_age_id_by_name($name = null) {
        $find_age = $this->db->select('id')->get_where('ages', array('name' => $name));

        if($find_age->num_rows() > 0) {
            $age_id = $find_age->result_array()[0]['id'];
        } else {
            $age_id = $this->new_record('ages', array('name' => $name));
        }

        return $age_id;
    }
*/

}
