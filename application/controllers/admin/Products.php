<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->lang->load('admin/products');
        $this->load->model('admin/products_model');

        $this->page_title->push(lang('menu_products'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(1, lang('menu_products'), 'admin/products');
    }

	public function index($page = 1) {
        $this->load->library('pagination');

        $config['base_url'] = base_url('admin/products/index');
        $config['total_rows'] = $this->products_model->get_count_record('products');
        $config['per_page'] = 50;
        //$config['num_links'] = floor($config['total_rows'] / $config['per_page']);
        $config['num_links'] = 15;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->data['search_term'] = $this->input->get('search');
        $this->pagination->initialize($config);

        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        if(isset($this->data['search_term'])) {
            $this->data['products'] = $this->products_model->get_all_products(array('search' => $this->data['search_term']));
        } else {
            $this->data['products'] = $this->products_model->get_all_products(array('per_page' => $config["per_page"], 'page' => $page));

            $this->data['pagination'] = $this->pagination->create_links();
        }

        $this->template->admin_render('admin/products/index', $this->data);
	}

    public function new_product() {
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'session'));

        $this->breadcrumbs->unshift(2, lang('menu_new_product'), 'admin/products/new');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->form_validation->set_rules('active', 'Active', 'alpha_numeric');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('code', 'Code', 'required|alpha_numeric');
        $this->form_validation->set_rules('brand', 'Brand', 'required|alpha_numeric');
        $this->form_validation->set_rules('categories', 'Category', 'required|alpha_numeric');
        $this->form_validation->set_rules('description_short', 'Short description');
        $this->form_validation->set_rules('description_long', 'Long description');
        $this->form_validation->set_rules('stock', 'Stock', 'required|alpha_numeric');
        $this->form_validation->set_rules('price', 'Price', 'numeric');
        $this->form_validation->set_rules('price_rec', 'Sale price', 'numeric');

        if ($this->form_validation->run()) {
            $product_data = array(
                    'status' => $this->input->post('active'),
                    'name' => $this->input->post('name'),
                    'code' => $this->input->post('code'),
                    'price' => $this->input->post('price'),
                    'price_rec' => $this->input->post('price_rec'),
                    'stock' => $this->input->post('stock'),
                    'stock_status' => $this->input->post('stock_status'),
                    'description_short' => $this->input->post('description_short'),
                    'description_long' => $this->input->post('description_long'),
                    'brand_id' => $this->input->post('brand'),
                    'category_id' => $this->input->post('categories'),
                    'subcategory_id' => $this->input->post('subcategories')
            );

            if($this->input->post('stock_status') == 0) {
                $product_data['stock_status'] = $this->input->post('stock') >= 10 ? 'in stoc' : ($this->input->post('stock') < 10 && $this->input->post('stock') > 0 ? 'stoc minim' : 'indisponibil');
            }

            $new_product_id = $this->products_model->new_record('products', $product_data);

            if ($new_product_id) {
                if($this->input->post('attributes')) {
                    $product_attributes = array();

                    foreach($this->input->post('attributes') as $key => $value) {
                        $product_attributes[$key] = $value[0];
                        $type_id = $this->products_model->get_record('attribute_type', array('name' => $key), 'id');
                        $attribute_id = $this->products_model->get_record_data('attribute_value', array('name' => $value[0], 'type_id' => $type_id['id']), array('name' => $value[0], 'type_id' => $type_id['id']), 'id');

                        $this->products_model->get_record_data('attribute_to_product', array('product_id' => $new_product_id, 'attribute_id' => $type_id['id']), array('product_id' => $new_product_id, 'attribute_id' => $attribute_id), 'attribute_id');
                    }
                }

                $gallery_session = $this->session->userdata('product_images');
                
                if($gallery_session) {
                    foreach($gallery_session as $gallery) {
                        $this->products_model->update_record('gallery', array('id' => $gallery), array('product_id' => $new_product_id));
                    }
                }

                $this->session->unset_userdata('product_images');

                redirect('admin/products/view_product/' . $new_product_id, 'refresh');
            }
        } else {
            $this->data['active'] = array(
                'name'    => 'active',
                'id'      => 'active',
                'value'   => '1',
                'class'   => 'checkbox-primary',
                'checked' => TRUE
            );
            $this->data['name'] = array(
                'name'  => 'name',
                'id'    => 'name',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('name'),
                'placeholder' => 'Puzzle'
            );
            $this->data['code'] = array(
                'name'  => 'code',
                'id'    => 'code',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('code'),
                'placeholder' => 'PM1234'
            );

            $brands = $this->products_model->get_records('brand', null, array('id', 'name'), 'name asc');
            $categories = $this->products_model->get_records('categories', array('brand_id' => $brands[0]['id']), array('id', 'name'), 'name asc');

            $this->data['brand_id'] = array_combine(array_column($brands, 'id'), array_column($brands, 'name'));
            $this->data['category_id'] = array_combine(array_column($categories, 'id'), array_column($categories, 'name'));
            $get_attributes = $this->products_model->get_records('attribute_type');
            $this->data['attributes'] = array_combine(array_column($get_attributes, 'id'), array_column($get_attributes, 'name'));
            array_unshift($this->data['attributes'], 'select attribute');

            $this->data['images'] = array(
                'name'  => 'images',
                'id'    => 'images',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('images')
            );
            $this->data['description_short'] = array(
                'name'  => 'description_short',
                'id'    => 'description_short',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('description_short'),
                'placeholder' => lang('products_short_description')
            );
            $this->data['description_long'] = array(
                'name'  => 'description_long',
                'id'    => 'description_long',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('description_long'),
                'placeholder' => lang('products_long_description')
            );
            $this->data['stock'] = array(
                'name'  => 'stock',
                'id'    => 'stock',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('stock'),
                'placeholder' => '12'
            );
            $this->data['stock_status'] = array('0' => 'stoc status', 'in stoc' => 'in stoc', 'stoc minim' => 'stoc minim', 'indisponibil' => 'indisponibil');
            $this->data['price'] = array(
                'name'  => 'price',
                'id'    => 'price',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('price'),
                'placeholder' => '200'
            );
            $this->data['price_rec'] = array(
                'name'  => 'price_rec',
                'id'    => 'price_rec',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('price_rec'),
                'placeholder' => '250'
            );

            $this->template->admin_render('admin/products/new', $this->data);
        }
    }

    public function edit_product($productid) {
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'session'));

        $this->breadcrumbs->unshift(2, lang('menu_edit_product'), 'admin/products/edit_product');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $product_data = $this->products_model->get_record('products', array('id' => $productid));
        $get_brands = $this->products_model->get_records('brand', null, array('id', 'name'), ' name asc');
        $get_categories = $this->products_model->get_records('categories', array('brand_id' => $product_data['brand_id'], 'subcategory_id' => '0'), array('id', 'name'), 'name asc');
        $get_subcategories = $this->products_model->get_records('categories', array('subcategory_id' => $product_data['category_id']), array('id', 'name'), 'name asc');
        $get_attributes = $this->products_model->get_records('attribute_type', null, '*', 'name asc');

        $this->data['attributes'] = array_combine(array_column($get_attributes, 'id'), array_column($get_attributes, 'name'));
        $this->data['product_attributes'] = $this->products_model->get_attributes($productid);
        $this->data['brand_id'] = array_combine(array_column($get_brands, 'id'), array_column($get_brands, 'name'));
        $this->data['category_id'] = array_combine(array_column($get_categories, 'id'), array_column($get_categories, 'name'));
        $this->data['subcategory_id'] = array('SubCategory') + array_combine(array_column($get_subcategories, 'id'), array_column($get_subcategories, 'name'));
        array_unshift($this->data['attributes'], 'select attribute');
        $this->data['subcategory_id'] = $this->data['subcategory_id'];

        $this->form_validation->set_rules('active', 'Active', 'alpha_numeric');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('code', 'Code', 'required|alpha_numeric');
        $this->form_validation->set_rules('brand', 'Brand', 'required|alpha_numeric');
        $this->form_validation->set_rules('categories', 'Category', 'required|alpha_numeric');
        $this->form_validation->set_rules('description_short', 'Short description');
        $this->form_validation->set_rules('description_long', 'Long description');
        $this->form_validation->set_rules('stock', 'Stock', 'required|alpha_numeric');
        $this->form_validation->set_rules('stock_status', 'Stock status', 'required');
        $this->form_validation->set_rules('price', 'Price', 'numeric');
        $this->form_validation->set_rules('price_rec', 'Sale price', 'numeric');

        if($this->form_validation->run()) {
            if($this->input->post('attributes')) {
                $product_attributes = $product_attributes2 = array();

                foreach($this->input->post('attributes') as $key => $value) {
                    $product_attributes[$key] = $value[0];
                }

                foreach($this->data['product_attributes'] as $attribute) {
                    $product_attributes2[$attribute['type']] = $attribute['value'];
                }

                foreach(array_diff($product_attributes2, $product_attributes) as $key => $value) {
                    $type_id = $this->products_model->get_record('attribute_type', array('name' => $key), 'id');
                    $attribute_id = $this->products_model->get_record('attribute_value', array('type_id' => $type_id['id'], 'name' => $value), 'id');

                    $this->products_model->delete_record('attribute_to_product', array('product_id' => $productid, 'attribute_id' => $attribute_id['id']));
                }

                foreach(array_diff($product_attributes, $product_attributes2) as $key => $value) {
                    if($key && $value) {
                        $type_id = $this->products_model->get_record('attribute_type', array('name' => $key), 'id');
                        $attribute_id = $this->products_model->get_record_data('attribute_value', array('name' => $value, 'type_id' => $type_id['id']), array('name' => $value, 'type_id' => $type_id['id']), 'id');

                        $this->products_model->get_record_data('attribute_to_product', array('product_id' => $productid, 'attribute_id' => $type_id['id']), array('product_id' => $productid, 'attribute_id' => $attribute_id), 'attribute_id');
                    }
                }

                $this->data['product_attributes'] = $this->products_model->get_attributes($productid);
            }

            $submited_product_data = array(
                    'status' => $this->input->post('active'),
                    'name' => $this->input->post('name'),
                    'code' => $this->input->post('code'),
                    'price' => $this->input->post('price'),
                    'price_rec' => $this->input->post('price_rec'),
                    'stock' => $this->input->post('stock'),
                    'stock_status' => $this->input->post('stock_status'),
                    'description_short' => $this->input->post('description_short'),
                    'description_long' => $this->input->post('description_long'),
                    'brand_id' => $this->input->post('brand'),
                    'category_id' => $this->input->post('categories'),
                    'subcategory_id' => $this->input->post('subcategories')
            );

            if($this->input->post('stock_status') == $product_data['stock_status']) {
                $submited_product_data['stock_status'] = $this->input->post('stock') >= 10 ? 'in stoc' : ($this->input->post('stock') < 10 && $this->input->post('stock') > 0 ? 'stoc minim' : 'indisponibil');
            }

            $this->products_model->update_record('products', array('id' => $productid), $submited_product_data);
            $product_data = $this->products_model->get_record('products', array('id' => $productid));
            $this->data['product_updated'] = 'Product updated successfully';

            $unselected_images = array_diff(array_column($this->products_model->get_records('gallery', array('product_id' => $productid), 'id'), 'id'), $this->input->post('images'));

            foreach($unselected_images as $unselected_image) {
                $this->products_model->delete_record('gallery', array('id' => $unselected_image));
            }

            $gallery_session = $this->session->userdata('product_images');

            if($gallery_session) {
	            foreach($gallery_session as $gallery) {
	                $this->products_model->update_record('gallery', array('id' => $gallery), array('product_id' => $productid));
	            }
	        }

            $this->session->unset_userdata('product_images');
        }

        $this->data['product_id'] = $productid;
        $this->data['brand'] = $this->products_model->get_record('brand', array('id' => $product_data['brand_id']), 'id');
        $this->data['category'] = $this->products_model->get_record('categories', array('id' => $product_data['category_id']), 'id');
        $this->data['subcategory'] = $this->products_model->get_record('categories', array('subcategory_id' => $product_data['subcategory_id']), 'id');
        $this->data['images'] = $this->products_model->get_records('gallery', array('product_id' => $productid), array('id', 'link'));
        $this->data['stock_status_selected'] = $product_data['stock_status'];

        $this->data['active'] = array(
            'name'    => 'active',
            'id'      => 'frm-test-elm-110-1',
            'value'   => '1',
            'checked' => $product_data['status']
        );
        $this->data['name'] = array(
            'name'  => 'name',
            'id'    => 'name',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $product_data['name'],
            'placeholder' => lang('products_name')
        );
        $this->data['code'] = array(
            'name'  => 'code',
            'id'    => 'code',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $product_data['code'],
            'placeholder' => lang('products_code')
        );
        $this->data['description_short'] = array(
            'name'  => 'description_short',
            'id'    => 'description_short',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $product_data['description_short'],
            'placeholder' => lang('products_short_description')
        );
        $this->data['description_long'] = array(
            'name'  => 'description_long',
            'id'    => 'description_long',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $product_data['description_long'],
            'placeholder' => lang('products_long_description')
        );
        $this->data['stock'] = array(
            'name'  => 'stock',
            'id'    => 'stock',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $product_data['stock'],
            'placeholder' => lang('products_stock')
        );
        $this->data['stock_status'] = array('in stoc' => 'in stoc', 'stoc minim' => 'stoc minim', 'indisponibil' => 'indisponibil');
        $this->data['price'] = array(
            'name'  => 'price',
            'id'    => 'price',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $product_data['price'],
            'placeholder' => lang('products_price')
        );
        $this->data['price_rec'] = array(
            'name'  => 'price_rec',
            'id'    => 'price_rec',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $product_data['price_rec'],
            'placeholder' => lang('products_price_rec')
        );

        $this->template->admin_render('admin/products/edit', $this->data);
    }

    public function view_product($id) {
        $this->breadcrumbs->unshift(2, lang('menu_view_product'), 'admin/products/new');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

    	$this->data['product'] = $this->products_model->get_record('products', array('id' => $id));
    	$this->data['product']['brand'] = $this->products_model->get_record('brand', array('id' => $this->data['product']['brand_id']), 'name');
    	$this->data['attributes'] = $this->products_model->get_attributes($id);
    	$this->data['product']['category'] = $this->products_model->get_record('categories', array('id' => $this->data['product']['category_id']), array('id', 'name'));
    	$this->data['product']['subcategory'] = $this->products_model->get_record('categories', array('subcategory_id' => $this->data['product']['category']['id']), 'name');
    	$this->data['product']['images'] = $this->products_model->get_records('gallery', array('product_id' => $this->data['product']['id']), 'link');

        $this->template->admin_render('admin/products/view', $this->data);
    }

    public function delete_product($id) {
    	$this->products_model->delete_record('products', array('id' => $id));
        $this->products_model->delete_record('gallery', array('product_id' => $id));

    	redirect('admin/products', 'refresh');
    }

    public function do_upload() {
        if($this->input->post('submit')){

            $path = './upload/images/';
            $this->load->library('upload');
            $this->load->helper(array('form', 'url'));

            $this->upload->initialize(array(
                "upload_path"       =>  $path,
                "allowed_types"     =>  "gif|jpg|png"
            ));

            if($this->upload->do_multi_upload("uploadfile")) {
                $this->load->model('admin/gallery_model');
                $this->load->library('session');

                $data['upload_data'] = $this->upload->get_multi_upload_data();

                $images_id = array();

                foreach($data['upload_data'] as $image) {
                    if($image['is_image']) {
                        $insert_image_data = $this->gallery_model->save(array('link' => base_url() . '/upload/images/' . $image['file_name']));

                        if($insert_image_data) {
                            $images_id[] = $insert_image_data;
                        }
                    }
                }

		        $this->session->set_userdata('product_images', $images_id);
		        $this->session->mark_as_temp('product_images', 900);

                $this->data['message'] = '<div class="alert alert-success"><strong>' . count($data['upload_data']) . '</strong> image(s) successfully uploaded.</div>';
            } else {
                $errors = array('error' => $this->upload->display_errors('<div class="alert alert-danger">', '</div>'));

                foreach($errors as $k => $error) {
                    $this->data['message'] .= $error;
                }
               
            }
        } else {
            $this->data['message'] = '<div class="alert alert-danger">An error occured, please try again later.</div>';
        }

        $this->load->view('json/upload_files', $this->data);
    }

}