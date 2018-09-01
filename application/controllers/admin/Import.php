<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends Admin_Controller {

    public function products() {
        $this->load->library(array('form_validation', 'session'));
        $this->load->helper(array('directory', 'file', 'language'));
        $this->lang->load('admin/main_sidebar');
        $this->load->config('common/dp_config');

        $this->page_title->push(lang('menu_import_products'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(1, lang('menu_import_products'), 'admin/import/products');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $data = array();

        $this->data['status_imported_products'] = $this->session->flashdata('status_imported_products');

        if($this->input->post('uploadFile')) {
            $this->form_validation->set_rules('file', '', 'callback_file_check');

            if($this->form_validation->run()) {

                $config['upload_path']   = $this->config->item('excel_dir') . '/products/';
                $config['allowed_types'] = 'xlsx|xls';
                $config['max_size']      = 7168;
                $this->load->library('upload', $config);

                if($this->upload->do_upload('file')) {
                    $uploadData = $this->upload->data();
                    $uploadedFile = $uploadData['file_name'];

                    $data['success_msg'] = 'File has been uploaded successfully.';
                } else {
                    $data['error_msg'] = $this->upload->display_errors();
                }
            }
        }

        $this->data['files'] = get_dir_file_info($this->config->item('excel_dir') . '/products/');

        $this->template->admin_render('admin/import/products', $this->data);
    }

    public function stock() {
        $this->load->library(array('form_validation', 'session'));
        $this->load->helper(array('directory', 'file', 'language'));
        $this->lang->load('admin/main_sidebar');
        $this->load->config('common/dp_config');

        $this->page_title->push(lang('menu_import_stock'));
        $this->data['pagetitle'] = $this->page_title->show();

        $this->breadcrumbs->unshift(1, lang('menu_import_stock'), 'admin/import/stock');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->data['status_imported_stock'] = $this->session->flashdata('status_imported_stock');

        if($this->input->post('uploadFile')) {
            $this->form_validation->set_rules('file', '', 'callback_file_check');

            if($this->form_validation->run()) {
                $config['upload_path']   = $this->config->item('excel_dir') . '/stock/';
                $config['allowed_types'] = '*';
                $config['max_size']      = 7168;
                $config['remove_spaces'] = TRUE;
                $this->load->library('upload', $config);
                $upload_message = array();

                if(!$this->upload->do_upload('file')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('error',$error['error']);
                    redirect('/admin/import/stock', 'refresh');
                } else {
                    $upload_message['success'] = 'File has been uploaded successfully.';
                }

                $this->data['upload_message'] = $upload_message;
            }
        }

        $this->data['upload_success'] = $this->session->flashdata('upload_success');
        $this->data['upload_error'] = $this->session->flashdata('upload_error');
        $this->data['files'] = get_dir_file_info($this->config->item('excel_dir') . '/stock/');

        $this->template->admin_render('admin/import/stock', $this->data);
    }

    public function file_check() {
        if (!$this->ion_auth->logged_in() OR !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        if(isset($_FILES['file']['name']) && strlen($_FILES['file']['name']) > 0) {
            if(in_array(get_mime_by_extension($_FILES['file']['name']), array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/octet-stream'))) {
                return true;
            } else {
                $this->form_validation->set_message('file_check', 'Please select only xlsx/xls file.');
                return false;
            }
        } else {
            $this->form_validation->set_message('file_check', 'Please choose a file to upload.');
            return false;
        }
    }

    public function import_products($name) {
        $this->load->config('common/dp_config');

        if(is_file(FCPATH . $this->config->item('excel_dir') . '/products/' . $name)) {
            $this->load->model('admin/products_model');
            $this->load->library(array('admin/excel', 'session'));

            $data = $products_updated = array();
            $j = $k = 0;

            $objPHPExcel = PHPExcel_IOFactory::load(FCPATH . $this->config->item('excel_dir') . '/products/' . $name);
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

            $data['totalrows'] = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
            $data['maxCell'] = $objWorksheet->getHighestRowAndColumn();
            $data['keys'] = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $data['maxCell']['column'] . '1');
            $data['attributes']['type'] = $this->products_model->get_records('attribute_type');
            $data['attributes']['available'] = array_intersect(array_column($data['attributes']['type'], 'name', 'id'), $data['keys'][0]);

            for($i = 2; $i <= $data['totalrows']; $i++) {
            	$data['import'] = array_combine($data['keys'][0], $objPHPExcel->getActiveSheet()->rangeToArray('A' . $i . ':' . $data['maxCell']['column'] . $i)[0]);

                if($data['import']['cod']) {
                    $product_data = array(
                        'code' => $data['import']['cod'],
                        'status' => 1
                    );

                    $data['product']['id'] = $this->products_model->get_record('products', array('code' => $data['import']['cod']), 'id')['id'];

                    if(array_key_exists('descriere_brand', $data['import'])) {
                        $data['product']['brand']['description'] = $data['import']['descriere_brand'];
                    } else {
                        $data['product']['brand']['description'] = '';
                    }

                    if(array_key_exists('descriere_categorie', $data['import'])) {
                        $data['product']['category']['description'] = $data['import']['descriere_categorie'];
                    } else {
                        $data['product']['category']['description'] = '';
                    } 

                    if(array_key_exists('brand', $data['import'])) {
                        $data['product']['brand']['id'] = $this->products_model->get_record_id_by('brand', array('name' => $data['import']['brand']), array('name' => $data['import']['brand'], 'description' => $data['product']['brand']['description']));

                        $product_data['brand_id'] = $data['product']['brand']['id'];
                    }

                    if(array_key_exists('categorie', $data['import'])) {
                        $data['product']['category']['id'] = $this->products_model->get_record_id_by('categories', array('name' => $data['import']['categorie']), array('brand_id' => $data['product']['brand']['id'], 'name' => $data['import']['categorie'], 'description' => $data['product']['category']['description']));

                        $product_data['category_id'] = $data['product']['category']['id'];
                    }

                    if(array_key_exists('denumire', $data['import'])) {
                        $product_data['name'] = $data['import']['denumire'];
                    }

                    if(array_key_exists('descriere', $data['import'])) {
                        $product_data['description_long'] = $data['import']['descriere'];
                    } else {
                        if(array_key_exists('descriere_scurta', $data['import'])) {
                            $product_data['description_short'] = $data['import']['descriere_scurta'];
                        }

                        if(array_key_exists('descriere_lunga', $data['import'])) {
                            $product_data['description_long'] = $data['import']['descriere_lunga'];
                        }
                    }

                    if(array_key_exists('pret_fara_tva', $data['import'])) {
                        $product_data['price'] = $data['import']['pret_fara_tva'];
                    }

                    if(array_key_exists('pret_recomandat', $data['import'])) {
                        $product_data['price_rec'] = $data['import']['pret_recomandat'];
                    }

                    if(array_key_exists('dimensiune_cutie', $data['import'])) {
                        $data['import']['dimensiune_cutie'] = $data['import']['dimensiune_cutie'];
                    } elseif (array_key_exists('lungime_cutie', $data['import']) || array_key_exists('latime_cutie', $data['import']) || array_key_exists('inaltime_cutie', $data['import'])) {
                        $data['import']['dimensiune_cutie'] = $data['import']['lungime_cutie'] . 'x' . $data['import']['latime_cutie'] . 'x' . $data['import']['inaltime_cutie'];
                    }

                    if(array_key_exists('dimensiune_produs', $data['import'])) {
                        $data['import']['dimensiune_produs'] = $data['import']['dimensiune_produs'];
                    } elseif (array_key_exists('lungime_produs', $data['import']) || array_key_exists('latime_produs', $data['import']) || array_key_exists('inaltime_produs', $data['import'])) {
                        $data['import']['dimensiune_produs'] = $data['import']['lungime_produs'] . 'x' . $data['import']['latime_produs'] . 'x' . $data['import']['inaltime_produs'];
                    }

                    if($data['product']['id']) {
                    	$this->products_model->update_record('products', array('id' => $data['product']['id']), $product_data);
                        if(!in_array($data['import']['cod'], $products_updated)) {
                            $products_updated[] = $data['import']['cod'];
                        }
                    } else {
                        $product_data['stock_status'] = 'indisponibil';
                        $data['product']['id'] = $this->products_model->new_record('products', $product_data);
                        $j++;
                    }

                    foreach($data['attributes']['available'] as $key => $value) {
                    	if(strlen($data['import'][$value]) > 1) {
                    		$attribute_value_id = $this->products_model->get_record_id_by('attribute_value', array('type_id' => $key, 'name' => $data['import'][$value]), array('type_id' => $key, 'name' => $data['import'][$value]));

                    		$this->products_model->get_record_data('attribute_to_product', array('product_id' => $data['product']['id'], 'attribute_id' => $attribute_value_id), array('product_id' => $data['product']['id'], 'attribute_id' => $attribute_value_id), 'attribute_id');
                    	}
                    }

                    foreach($data['import'] as $key => $value) {
                        if(strpos($key, 'imagine') !== false && isset($value)) {
                            $this->products_model->get_record_data('gallery', array('product_id' => $data['product']['id'], 'link' => $value), array('product_id' => $data['product']['id'], 'link' => $value));
                        }
                    }
                } else {
                    $k++;
                }
            }

            $this->session->set_flashdata('status_imported_products', array('products_imported' => $j, 'products_updated' => count($products_updated), 'products_invalid' => $k));

            redirect('/admin/import/products');
        } else {
            redirect('/admin');
        }
    }

///////////////TEMPORAR
    private function fetch_data_v1() {
        $this->load->dbutil();

        return $this->db->query("
                                    SELECT
                                        p.name,
                                        p.code,
                                        p.description_long,
                                        p.stock,
                                        p.stock_status,
                                        p.price,
                                        p.price_rec,
                                        b.name AS brand,
                                        b.description AS brand_description,
                                        c.name AS category,
                                        c.description AS category_description,
                                        GROUP_CONCAT(av.name) AS attribute_value,
                                        GROUP_CONCAT(at.name) AS attribute_type,
                                        (
                                            SELECT
                                                GROUP_CONCAT(g.link)
                                            FROM
                                                gallery g
                                            WHERE
                                                g.product_id = p.id
                                        ) AS images
                                    FROM products AS p
                                        INNER JOIN brand AS b ON p.brand_id = b.id
                                        INNER JOIN categories AS c ON p.category_id = c.id
                                        INNER JOIN attribute_to_product AS atp ON p.id = atp.product_id
                                        INNER JOIN attribute_value AS av ON atp.attribute_id = av.id
                                        INNER JOIN attribute_type AS at ON av.type_id = at.id
                                    WHERE
                                        p.status = 1
                                    GROUP BY
                                        p.id
                                    ORDER BY
                                        brand,
                                        category
                                ")->result_array();
    }

    private function fetch_data_v2() {
        $this->load->dbutil();
        $data = array();

        $data['products'] = $this->db->query("
                                    SELECT
                                        p.name,
                                        p.code,
                                        p.description_short,
                                        p.description_long,
                                        p.stock,
                                        p.stock_status,
                                        p.price,
                                        p.price_rec,
                                        b.name AS brand,
                                        c.name AS category,
                                        GROUP_CONCAT(av.name) AS attribute_value,
                                        GROUP_CONCAT(at.name) AS attribute_type,
                                        (
                                            SELECT
                                                GROUP_CONCAT(name)
                                            FROM
                                                categories sc
                                            WHERE
                                                sc.subcategory_id = c.id
                                        ) AS subcategory,
                                        (
                                            SELECT
                                                GROUP_CONCAT(g.link)
                                            FROM
                                                gallery g
                                            WHERE
                                                g.product_id = p.id
                                        ) AS images
                                    FROM products AS p
                                        INNER JOIN brand AS b ON p.brand_id = b.id
                                        INNER JOIN categories AS c ON p.category_id = c.id
                                        INNER JOIN attribute_to_product AS atp ON p.id = atp.product_id
                                        INNER JOIN attribute_value AS av ON atp.attribute_id = av.id
                                        INNER JOIN attribute_type AS at ON av.type_id = at.id
                                    WHERE
                                        p.status = 1
                                    GROUP BY
                                        p.id
                                    ORDER BY
                                        brand,
                                        category
                                ")->result_array();

        $data['brands'] = $this->db->query("SELECT name, description FROM brand WHERE CHAR_LENGTH(description) > 0 ORDER BY name ASC")->result_array();
        $data['categories'] = $this->db->query("SELECT name, description FROM categories WHERE subcategory_id = 0 AND CHAR_LENGTH(description) > 0 ORDER BY name ASC")->result_array();

        return $data;
    }

    private function get_xml_data_v1() {
        $this->load->helper('xml');

        $xml_data = $this->fetch_data_v1();
        $xml = new DomDocument('1.0', 'UTF-8');
        $xml->FormatOutput = true;

        $products = $xml->createElement('produse');
        $xml->appendChild($products);

        foreach($xml_data as $data) {
            $product = $xml->createElement('produs');
            $products->appendChild($product);

            $name = $xml->createElement('denumire', htmlspecialchars($data['name']));
            $product->appendChild($name);

            $description_long = $xml->createElement('descriere', htmlspecialchars($data['description_long']));
            $product->appendChild($description_long);

            $code = $xml->createElement('cod', $data['code']);
            $product->appendChild($code);

            $price = $xml->createElement('pret_fara_tva', $data['price']);
            $product->appendChild($price);

            $price_rec = $xml->createElement('pret_recomandat', $data['price_rec']);
            $product->appendChild($price_rec);

            $category = $xml->createElement('categorie', htmlspecialchars($data['category']));
            $product->appendChild($category);

            $category_description = $xml->createElement('descriere_categorie', htmlspecialchars($data['category_description']));
            $product->appendChild($category_description);

            $brand = $xml->createElement('brand', htmlspecialchars($data['brand']));
            $product->appendChild($brand);

            $brand_description = $xml->createElement('descriere_brand', htmlspecialchars($data['brand_description']));
            $product->appendChild($brand_description);

            $stock = $xml->createElement('disponibilitate', $data['stock_status'] ? $data['stock_status'] : 'indisponibil');
            $product->appendChild($stock);

            $attributes_type = explode(',', $data['attribute_type']);
            $attributes_value = explode(',', $data['attribute_value']);
                
                
                if(count($attributes_type) == count($attributes_value)) {
                    $attribut = array_combine($attributes_type, $attributes_value);

                    $varsta = $xml->createElement('varsta', $attribut['varsta']);
                    $product->appendChild($varsta);

                    $dimensiune_produs = isset($attribut['dimensiune_produs']) ? $xml->createElement('dimensiune_produs', $attribut['dimensiune_produs']) : $xml->createElement('dimensiune_produs', '');
                    $product->appendChild($dimensiune_produs);

                    $dimensiune_cutie = $xml->createElement('dimensiune_cutie', $attribut['dimensiune_cutie']);
                    $product->appendChild($dimensiune_cutie);
                }

            if($data['images']) {
                $images = $xml->createElement('imagini');
                $product->appendChild($images);
                $get_images = explode(',', $data['images']);
                $i = 0;

                foreach($get_images as $image) {
                    $i++;

                    $image = $xml->createElement('imagine' . $i, $image);
                    $images->appendChild($image);
                }
            }
        }

        return $xml->saveXML();
    }

    private function get_xml_data_v2() {
        $this->load->helper('xml');

        $xml_data = $this->fetch_data_v2();
        $xml = new DomDocument('1.0', 'UTF-8');
        $xml->FormatOutput = true;

        $products_data = $xml->createElement('date_produse');
        $xml->appendChild($products_data);
 
        $products = $xml->createElement('produse');
        $products_data->appendChild($products);

        $brands = $xml->createElement('branduri');
        $products_data->appendChild($brands);

        $categories = $xml->createElement('categorii');
        $products_data->appendChild($categories);

        foreach($xml_data['products'] as $data) {
            $product = $xml->createElement('produs');
            $products->appendChild($product);

            $code = $xml->createElement('cod', $data['code']);
            $product->appendChild($code);

            $name = $xml->createElement('nume', htmlspecialchars($data['name']));
            $product->appendChild($name);

            if($data['description_short']) {
                $description_short = $xml->createElement('descriere_scurta', htmlspecialchars($data['description_short']));
                $product->appendChild($description_short);
            }

            $description_long = $xml->createElement('descriere_lunga', htmlspecialchars($data['description_long']));
            $product->appendChild($description_long);

            $brand = $xml->createElement('brand', htmlspecialchars($data['brand']));
            $product->appendChild($brand);

            $category = $xml->createElement('categorie', htmlspecialchars($data['category']));
            $product->appendChild($category);

            if($data['subcategory']) {
                $subcategory = $xml->createElement('subcategorie', htmlspecialchars($data['subcategory']));
                $product->appendChild($subcategory);
            }

            $attributes = $xml->createElement('atribute');
            $product->appendChild($attributes);

            $attributes_type = explode(',', $data['attribute_type']);
            $attributes_value = explode(',', $data['attribute_value']);

            if(count($attributes_type) == count($attributes_value)) {
                $attribut = array_combine($attributes_type, $attributes_value);

                foreach($attribut as $key => $value) {
                    if($key == 'dimensiune_cutie' || $key == 'dimensiune_produs') {
                        $attribute = $xml->createElement($key);
                        $attributes->appendChild($attribute);

                        $i = 0;
                        $dimensions = explode('x', strtolower($value));
                        $dimensions_order = array('lungime', 'latime', 'inaltime');

                        foreach($dimensions as $dimension) {
                            $dimension = preg_replace('/\\s/', '', $dimension);
                            $dimension_type = $xml->createElement($dimensions_order[$i], $dimension);
                            $attribute->appendChild($dimension_type);

                            $i++;
                        }
                    } else {
                        $attribute = $xml->createElement($key, $value);
                        $attributes->appendChild($attribute);
                    }
                }
            }

            $price = $xml->createElement('pret', $data['price']);
            $product->appendChild($price);

            $price_rec = $xml->createElement('pret_recomandat', $data['price_rec']);
            $product->appendChild($price_rec);

            $stock = $xml->createElement('stoc', $data['stock_status'] ? $data['stock_status'] : 'indisponibil');
            $product->appendChild($stock);

            if($data['images']) {
                $images = $xml->createElement('imagini');
                $product->appendChild($images);
                $get_images = explode(',', $data['images']);
                $i = 0;

                foreach($get_images as $image) {
                    $i++;

                    $image = $xml->createElement('imagine' . $i, $image);
                    $images->appendChild($image);
                }
            }
        }

        foreach($xml_data['brands'] as $brand_data ) {
            $brand = $xml->createElement('brand');
            $brands->appendChild($brand);

            $brand_name = $xml->createElement('nume', htmlspecialchars($brand_data['name']));
            $brand->appendChild($brand_name);

            $brand_description = $xml->createElement('descriere', htmlspecialchars($brand_data['description']));
            $brand->appendChild($brand_description);
        }

        foreach($xml_data['categories'] as $category_data) {
            $ccategory = $xml->createElement('categorie');
            $categories->appendChild($ccategory);

            $category_name = $xml->createElement('nume', htmlspecialchars($category_data['name']));
            $ccategory->appendChild($category_name);

            $category_description = $xml->createElement('descriere', htmlspecialchars($category_data['description']));
            $ccategory->appendChild($category_description);
        }

        return $xml->saveXML();
    }
///////////////TEMPORAR

    public function import_stock($name = null) {
        $this->load->config('common/dp_config');

        if(is_file(FCPATH . $this->config->item('excel_dir') . '/stock/' . $name)) {
            $this->load->model('admin/products_model');
            $this->load->library(array('admin/excel', 'session'));

            $product_stock_pieces = $product_stock_skipped = 0;
            $product_stock_updated = array();

            $objPHPExcel = PHPExcel_IOFactory::load(FCPATH . $this->config->item('excel_dir') . '/stock/' . $name);

            $totalrows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $maxCell = $objWorksheet->getHighestRowAndColumn();

            $keys = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . '1');

            $this->products_model->update_record('products', '1=1', array('stock' => '0', 'stock_status' => 'indisponibil'));

            for($i = 2; $i <= $totalrows; $i++) {
                $stock_data = array_combine($keys[0], $objPHPExcel->getActiveSheet()->rangeToArray('A' . $i . ':' . $maxCell['column'] . $i)[0]);

                $stock_status = $stock_data['Disponibil'] >= 10 ? 'in stoc' : ($stock_data['Disponibil'] <= 10 && $stock_data['Disponibil'] > 0 ? 'stoc minim' : 'indisponibil');

                if($this->products_model->update_record('products', array('code' => $stock_data['Simbol']), array('stock' => $stock_data['Disponibil'], 'stock_status' => $stock_status))) {
                    if(!in_array($stock_data['Simbol'], $product_stock_updated)) {//$product_stock_updated++;
                        $product_stock_updated[] = $stock_data['Simbol'];
                    }

                    $product_stock_pieces += $stock_data['Disponibil'];
                } else {
                    $product_stock_skipped++;
                }
            }

            file_put_contents(FCPATH . 'feed_v1.xml', $this->get_xml_data_v1());///////////////TEMPORAR
            file_put_contents(FCPATH . 'feed_v2.xml', $this->get_xml_data_v2());///////////////TEMPORAR

            $this->session->set_flashdata('status_imported_stock', array('product_stock_updated' => count($product_stock_updated), 'product_stock_pieces' => $product_stock_pieces, 'product_stock_missed' => $product_stock_skipped));

            redirect('/admin/import/stock');
        } else {
            redirect('/admin');
        }
    }

    public function delete_file($type = null, $name = null) {
        $this->load->config('common/dp_config');

        if(is_file(FCPATH . $this->config->item('excel_dir') . '/' . $type . '/' . $name)) {
            unlink(FCPATH . $this->config->item('excel_dir') . '/' . $type . '/' . $name);

            redirect('/admin/import/' . $type, 'refresh');
        } else {
            redirect('/admin');
        }
    }
}