<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Section_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_brand_id_by_name($name = null) {
        $find_brand = $this->db->select('id')->get_where('brand', array('name' => $name));

        if($find_brand->num_rows() > 0) {
            $brand_id = $find_brand->result_array()[0]['id'];
        } else {
            $brand_id = $this->new_brand(array('name' => $name));
        }

        return $brand_id;
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

    //select and count works only for brands, ages, categories and depends of table name
    public function get_records_count($table, $data) {
        $this->db->select($data . ', COUNT(' . $table . '_id) AS count_' . $table)->join('products', $table . '.id = products.' . $table . '_id')->group_by($table . '_id')->order_by('name');

        return $this->db->from($table)->get()->result_array();
    }

    /*public function count_records($table, $params = null) {
        $this->db->select('id')->from($table)->where($params);
        $q = $this->db->get();

        return $q->num_rows();
    }*/

    public function insert_record($table, $data) {
        $this->db->insert($table, $data);

        return $this->db->insert_id();
    }

    public function update_record($table = null, $params = null, $data = null) {
        $this->db->where($params);

        return $this->db->update($table, $data);
    }

    public function delete_record($table = null, $data = null) {
        return $this->db->delete($table, $data);
    }

}
