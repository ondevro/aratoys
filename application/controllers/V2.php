<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class V2 extends Public_Controller {

    private function fetch_data() {
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

    private function get_xml_data() {
    	$this->load->helper('xml');

    	$xml_data = $this->fetch_data();
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

    private function get_json_data() {
		$this->load->dbutil();

		$config = array (
		        'delimiter'     => ',',
		        'newline'       => "\r\n",
		        'enclosure'     => '"'
		);

		return $this->dbutil->csv_from_result($this->fetch_data(), $config['delimiter'], $config['newline'], $config['enclosure']);
    }

    private function check_file_feed($file_type) {
		$file = FCPATH . 'feed_v2.' . $file_type;
		//$opened_file = fopen($file, "w+");

		if(date("d/m/Y", filemtime($file)) < date("d/m/Y", time())) {
			$result = $file_type == 'xml' ? $this->get_xml_data() : $this->get_json_data();

			//fwrite($opened_file, $result);
			file_put_contents($file, $result);
		} else {
			$result = file_get_contents($file);//fgets($opened_file, filesize($file));
		}
		//fclose($opened_file);
		return $result;
    }

	public function xml() {
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$this->data['xml_data'] = $this->check_file_feed('xml');

		$this->load->view('public/xml', $this->data);
	}

	public function json() {
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$this->data['json_data'] = $this->check_file_feed('json');

		$this->load->view('public/json', $this->data);
	}
}