<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Export_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_products() {
        $this->db->select('products.name, products.code, products.description_short, products.description_long, products.stock_status, products.price, products.price_rec, brand.name as brand_name, brand.description as brand_description, categories.name as category_name, categories.description as category_description, (SELECT categories.name FROM categories WHERE id = products.subcategory_id LIMIT 1) AS subcategory_name, GROUP_CONCAT(DISTINCT(av.name)) AS attribute_value, GROUP_CONCAT(DISTINCT(at.name)) AS attribute_type, GROUP_CONCAT(DISTINCT(gallery.link)) as images')->from('products')->join('brand', 'products.brand_id = brand.id')->join('categories', 'products.category_id = categories.id')->join('attribute_to_product atp', 'products.id = atp.product_id')->join('attribute_value av', 'atp.attribute_id = av.id')->join('attribute_type at', 'av.type_id = at.id')->join('gallery', 'products.id = gallery.product_id')->group_by('products.id')->order_by('brand_name asc, category_name asc, products.name asc');

        return $this->db->get()->result_array();
    }

}
