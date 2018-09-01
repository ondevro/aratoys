<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('admin/dashboard_model');
    }

	public function index() {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        } else {
            $this->page_title->push(lang('menu_dashboard'));
            $this->data['pagetitle'] = $this->page_title->show();

            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            $count_products = $this->dashboard_model->get_count_record('products');
            $count_products_in_stock = $this->dashboard_model->get_count_record('products', array('stock >' => '0'));

            $this->data['count_users'] = $this->dashboard_model->get_count_record('users');
            $this->data['count_groups'] = $this->dashboard_model->get_count_record('groups');
            $this->data['count_products'] = $count_products;
            $this->data['count_products_in_stock'] = $count_products_in_stock;
            if($count_products_in_stock > 0) {
                $this->data['products_in_stock_percent'] = round(($count_products_in_stock / $count_products) * 100, 2);
            }
            $this->data['count_brands'] = $this->dashboard_model->get_count_record('brand');
            $this->data['count_categories'] = $this->dashboard_model->get_count_record('categories');
            $this->data['count_attributes'] = $this->dashboard_model->get_count_record('attribute_type');
            $this->data['stock_pieces'] = $this->dashboard_model->stock_pieces();

            $this->data['xml_v1_generated_date'] = date('d-m-Y G:i', filemtime(FCPATH . 'feed_v1.xml'));
            $this->data['xml_v2_generated_date'] = date('d-m-Y G:i', filemtime(FCPATH . 'feed_v2.xml'));
            $this->data['json_v1_generated_date'] = date('d-m-Y G:i', filemtime(FCPATH . 'feed_v1.json'));
            $this->data['json_v2_generated_date'] = date('d-m-Y G:i', filemtime(FCPATH . 'feed_v2.json'));

            $this->template->admin_render('admin/dashboard/index', $this->data);
        }
	}
}
