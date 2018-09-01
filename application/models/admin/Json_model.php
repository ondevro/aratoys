<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Json_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_categories_by_brand_id($id) {
        return $this->db->select('id, name')->order_by('name asc')->get_where('categories', array('brand_id' => $id, 'subcategory_id' => '0'))->result_array();
    }

    public function get_subcategories_by_category_id($id) {
        return $this->db->select('id, name')->order_by('name asc')->get_where('categories', array('subcategory_id' => $id))->result_array();
    }

    public function get_attribute_values_by_type_name($type_name, $value) {
        $this->db->select('av.name AS label, av.id as value')->from('attribute_value av')->join('attribute_type at', 'av.type_id = at.id')->where("at.name = '" . $type_name . "'")->like('av.name', $value, 'after');

        return $this->db->get()->result_array();
    }    
}
