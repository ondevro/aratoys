<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class V1 extends Public_Controller {

    private function fetch_data() {
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

    /*private function get_xml_data() {
		$this->load->dbutil();

		$config = array (
		        'root'          => 'products',
		        'element'       => 'product',
		        'newline'       => "\n",
		        'tab'           => "\t"
		);

    	return $this->dbutil->xml_from_result($this->fetch_data(), $config);
    }*/

    private function get_xml_data() {
    	$this->load->helper('xml');

    	$xml_data = $this->fetch_data();
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
		$file = FCPATH . 'feed_v1.' . $file_type;
		//$opened_file = fopen($file, "w+");

		if(date("d/m/Y", filemtime($file)) < date("d/m/Y", time())) {
			$result = $file_type == 'xml' ? $this->get_xml_data() : $this->get_json_data();

			//fwrite($opened_file, $result);
			file_put_contents($file, $result);
		} else {
	$ch = curl_init('http://feed.aratoys.ro/feed_v1.' . $file_type);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec($ch);
	curl_close($ch);
			//$result = implode('', file($file));//file_get_contents($file)//fgets($opened_file, filesize($file));
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
