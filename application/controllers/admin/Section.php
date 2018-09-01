<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        redirect('admin/admin', 'refresh');
    }

	public function brands() {
        $this->page_title->push(lang('menu_section_brands'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(2, lang('menu_section_brands'), 'admin/section/brands');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->load->helper(array('form', 'language'));
        $this->load->model('admin/section_model');
        $this->lang->load('admin/sections');

        $this->form_validation->set_rules('name', lang('section_name'), 'required');
        $this->form_validation->set_rules('description', lang('section_description'));

        if ($this->form_validation->run()) {
            $brand_data = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description')
            );

            $this->section_model->insert_record('brand', $brand_data);
            $this->data['brand_status']['added']  = 'Brand <strong>' . $this->input->post('name') . '</strong> added successfully';
        }

        $this->data['brands'] = $this->section_model->get_records_count('brand', 'brand.id, brand.name, brand.description');

        $this->data['name'] = array(
            'name'  => 'name',
            'id'    => 'name',
            'type'  => 'text',
            'class' => 'form-control',
            'placeholder' => lang('section_name')
        );

        $this->data['description'] = array(
            'name'  => 'description',
            'id'    => 'description',
            'type'  => 'text',
            'class' => 'form-control',
            'placeholder' => lang('section_description')
        );

        $this->template->admin_render('admin/section/brand_list', $this->data);
	}

    public function edit_brand($id) {
        $this->page_title->push(lang('menu_section_brands'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(2, lang('menu_section_brands'), 'admin/section/brands');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->load->helper(array('form', 'language'));
        $this->load->model('admin/section_model');
        $this->lang->load('admin/sections');

        $this->form_validation->set_rules('name', lang('section_name'), 'required');
        $this->form_validation->set_rules('description', lang('section_description'));

        if ($this->form_validation->run()) {
            $brand_data = array(
                    'name' => $this->input->post('name'),
                    'description' => $this->input->post('description')
            );

            $this->section_model->update_record('brand', array('id' => $id), $brand_data);
            $this->data['brand_status']['updated']  = 'Brand <strong>' . $this->input->post('name') . '</strong> updated successfully';
        }  

        $this->data['brands'] = $this->section_model->get_records_count('brand', 'brand.id, brand.name, brand.description');
        $this->data['brand'] = $this->section_model->get_record('brand', array('id' => $id));

        $this->data['name'] = array(
            'name'  => 'name',
            'id'    => 'name',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->data['brand']['name'],
            'placeholder' => lang('section_name')
        );

        $this->data['description'] = array(
            'name'  => 'description',
            'id'    => 'description',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->data['brand']['description'],
            'placeholder' => lang('section_description')
        );

        $this->template->admin_render('admin/section/brand_list', $this->data);
    }

    public function delete_brand($id) {
        $this->load->model('admin/section_model');
        $this->section_model->delete_record('brand', array('id' => $id));

        redirect('admin/section/brands');
    }

    public function categories() {
        $this->page_title->push(lang('menu_section_categories'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(2, lang('menu_section_categories'), 'admin/section/categories');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->load->helper(array('form', 'language'));
        $this->load->model('admin/section_model');
        $this->lang->load('admin/sections');

        $this->form_validation->set_rules('name', lang('section_name'), 'required');
        $this->form_validation->set_rules('brand', lang('section_brand'), 'required|numeric');
        $this->form_validation->set_rules('description', lang('section_description'));

        if ($this->form_validation->run()) {
            $categories_data = array(
                    'name' => $this->input->post('name'),
                    'brand_id' => $this->input->post('brand'),
                    'description' => $this->input->post('description'),
                    'subcategory_id' => $this->input->post('categories')
            );

            $this->section_model->insert_record('categories', $categories_data);
            $this->data['category_status']['added']  = '(sub)Category <strong>' . $this->input->post('name') . '</strong> added successfully';
        }

        $brands = $this->section_model->get_records('brand', null, '*', 'name asc');
        $this->data['brand'] = array('Brand') + array_combine(array_column($brands, 'id'), array_column($brands, 'name'));
        $this->data['selected_brand']['brand_id'] = '';

        $categories = $this->section_model->get_records('categories', null, '*', 'name asc');
        $this->data['brand_categories'] = array('Subcategory') + $this->data['categorie'] = array_combine(array_column($categories, 'id'), array_column($categories, 'name'));
        $this->data['selected_category']['subcategory_id'] = '';

        foreach($brands as $brand) {
            $this->data['brands'][] = array('id' => $brand['id'], 'name' => $brand['name']);

            foreach($categories as $category) {
                if($brand['id'] == $category['brand_id']) {
                    if($category['subcategory_id'] > 0) {
                        $this->data['subcategories'][$category['subcategory_id']][] = array('id' => $category['id'], 'name' => $category['name'], 'description' => $category['description']);
                    } else {
                        $this->data['categories'][$brand['id']][] = array('id' => $category['id'], 'name' => $category['name'], 'description' => $category['description']);
                    }
                }
            }
        }

        $this->data['name'] = array(
            'name'  => 'name',
            'id'    => 'name',
            'type'  => 'text',
            'class' => 'form-control',
            'placeholder' => lang('section_name')
        );

        $this->data['description'] = array(
            'name'  => 'description',
            'id'    => 'description',
            'type'  => 'text',
            'class' => 'form-control',
            'placeholder' => lang('section_description')
        );

        $this->template->admin_render('admin/section/categories_list', $this->data);
    }


    public function edit_category($id) {
        $this->page_title->push(lang('menu_section_categories'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(2, lang('menu_section_categories'), 'admin/section/categories');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->load->helper(array('form', 'language'));
        $this->load->model('admin/section_model');
        $this->lang->load('admin/sections');

        $this->form_validation->set_rules('name', lang('section_name'), 'required');
        $this->form_validation->set_rules('brand', lang('section_brand'), 'required');
        $this->form_validation->set_rules('description', lang('section_description'));

        if ($this->form_validation->run()) {
            $categories_data = array(
                    'name' => $this->input->post('name'),
                    'brand_id' => $this->input->post('brand'),
                    'description' => $this->input->post('description'),
                    'subcategory_id' => $this->input->post('categories')
            );

            $this->section_model->update_record('categories', array('id' => $id), $categories_data);
            $this->data['category_status']['updated']  = '(sub)Category <strong>' . $this->input->post('name') . '</strong> updated successfully';
        }

        $brands = $this->section_model->get_records('brand', null, '*', 'name asc');
        $this->data['brand'] = array_combine(array_column($brands, 'id'), array_column($brands, 'name'));
        $this->data['selected_brand'] = $this->section_model->get_record('categories', array('id' => $id), 'brand_id');

        $brand_categories = $this->section_model->get_records('categories', array('brand_id' => $this->data['selected_brand']['brand_id']), 'id, name', 'name asc');
        $this->data['brand_categories'] = array('Subcategory') + array_combine(array_column($brand_categories, 'id'), array_column($brand_categories, 'name'));
        $categories = $this->section_model->get_records('categories', null, '*', 'name asc');
        $this->data['categorie'] = array_combine(array_column($categories, 'id'), array_column($categories, 'name'));
        $this->data['selected_category'] = $this->section_model->get_record('categories', array('id' => $id));

        foreach($brands as $brand) {
            $this->data['brands'][] = array('id' => $brand['id'], 'name' => $brand['name']);

            foreach($categories as $category) {
                if($brand['id'] == $category['brand_id']) {
                    if($category['subcategory_id'] > 0) {
                        $this->data['subcategories'][$category['subcategory_id']][] = array('id' => $category['id'], 'name' => $category['name'], 'description' => $category['description']);
                    } else {
                        $this->data['categories'][$brand['id']][] = array('id' => $category['id'], 'name' => $category['name'], 'description' => $category['description']);
                    }
                }
            }
        }

        $this->data['name'] = array(
            'name'  => 'name',
            'id'    => 'name',
            'type'  => 'text',
            'class' => 'form-control',
            'placeholder' => lang('section_name'),
            'value' => $this->data['selected_category']['name']
        );

        $this->data['description'] = array(
            'name'  => 'description',
            'id'    => 'description',
            'type'  => 'text',
            'class' => 'form-control',
            'placeholder' => lang('section_description'),
            'value' => $this->data['selected_category']['description']
        );

        $this->template->admin_render('admin/section/categories_list', $this->data);
    }

    public function delete_category($id) {
        $this->load->model('admin/section_model');

        foreach($this->section_model->get_records('categories', array('subcategory_id' => $id), 'id') as $category) {
            $this->section_model->update_record('categories', array('id' => $category['id']), array('subcategory_id' => 0));
        }

        $this->section_model->delete_record('categories', array('id' => $id));

        redirect('admin/section/categories');
    }

    public function attributes() {
        $this->page_title->push(lang('menu_section_attributes'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(2, lang('menu_section_attributes'), 'admin/section/attributes');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->load->helper(array('form', 'language'));
        $this->load->model('admin/section_model');
        $this->lang->load('admin/sections');

        $this->form_validation->set_rules('name', lang('section_name'), 'required');

        if ($this->form_validation->run()) {
            $attributes_data = array(
                    'name' => $this->input->post('name')
            );

            $this->section_model->insert_record('attribute_type', $attributes_data);
        }

        $this->data['attributes'] = $this->section_model->get_records('attribute_type');

        $this->data['name'] = array(
            'name'  => 'name',
            'id'    => 'name',
            'type'  => 'text',
            'class' => 'form-control',
            'placeholder' => lang('section_name')
        );

        $this->template->admin_render('admin/section/attributes_list', $this->data);
    }

    public function edit_attribute($id) {
        $this->page_title->push(lang('menu_section_attributes'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(2, lang('menu_section_ages'), 'admin/section/attributes');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->load->helper(array('form', 'language'));
        $this->load->model('admin/section_model');
        $this->lang->load('admin/sections');

        $this->form_validation->set_rules('name', lang('section_name'), 'required');

        if ($this->form_validation->run()) {
            $attribute_data = array(
                    'name' => $this->input->post('name')
            );

            $this->section_model->update_record('attribute_type', array('id' => $id), $attribute_data);
        }

        $this->data['attributes'] = $this->section_model->get_records('attribute_type');
        $this->data['attribute'] = $this->section_model->get_record('attribute_type', array('id' => $id));

        $this->data['name'] = array(
            'name'  => 'name',
            'id'    => 'name',
            'type'  => 'text',
            'class' => 'form-control',
            'value' => $this->data['attribute']['name'],
            'placeholder' => lang('section_name')
        );

        $this->template->admin_render('admin/section/attributes_list', $this->data);
    }

    public function delete_attribute($id) {
        $this->load->model('admin/section_model');
        $this->section_model->delete_record('attribute_type', array('id' => $id));

        redirect('admin/section/attributes');
    }
}