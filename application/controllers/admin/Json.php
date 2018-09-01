<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('admin/json_model');
    }

    public function index() {
        redirect('/', 'refresh');
    }

    public function get_brand_categories($brandID = 0) {
        $options = array(
            'options' => array('min_range' => 1)
        );

        if (filter_var($brandID, FILTER_VALIDATE_INT, $options) !== FALSE) {
            $this->data['json_data'] = json_encode($this->json_model->get_categories_by_brand_id($brandID));

            $this->load->view('json/json_data', $this->data);
        }
    }

    public function get_categories_subcategories($categoryID = 0) {
        $options = array(
            'options' => array('min_range' => 1)
        );

        if (filter_var($categoryID, FILTER_VALIDATE_INT, $options) !== FALSE) {
            $this->data['json_data'] = json_encode($this->json_model->get_subcategories_by_category_id($categoryID));

            $this->load->view('json/json_data', $this->data);
        }
    }

    public function get_attribute_values($typeName, $attributeValue) {
        $options = array(
            'options' => array('min_range' => 1)
        );

        if (filter_var($attributeValue, FILTER_VALIDATE_INT, $options) !== FALSE) {
            $this->data['json_data'] = json_encode($this->json_model->get_attribute_values_by_type_name($typeName, $attributeValue));

            $this->load->view('json/json_data', $this->data);
        }
    }
}