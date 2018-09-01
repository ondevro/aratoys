<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends Admin_Controller {

    public function excel() {
        $this->load->library('admin/excel');

        $products = $this->db->query("
                                    SELECT
                                        p.name,
                                        p.code,
                                        p.description_short,
                                        p.description_long,
                                        p.stock,
                                        p.stock_status,
                                        p.price,
                                        p.price_rec,
                                        b.name AS brand_name,
                                        c.name AS category_name,
                                        GROUP_CONCAT(av.name) AS attribute_value,
                                        GROUP_CONCAT(at.name) AS attribute_type,
                                        (
                                            SELECT
                                                GROUP_CONCAT(name)
                                            FROM
                                                categories sc
                                            WHERE
                                                sc.subcategory_id = c.id
                                        ) AS subcategory_name,
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
                                        brand_name,
                                        p.name
                                ")->result_array();

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'cod');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'denumire');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'descriere_scurta');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'descriere_lunga');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'brand');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'categorie');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'subcategorie');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'pret_fara_tva');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'pret_recomandat');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'disponibilitate');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'lungime_cutie');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'latime_cutie');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'inaltime_cutie');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'lungime_produs');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'latime_produs');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', 'inaltime_produs');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'varsta');
        $objPHPExcel->getActiveSheet()->setCellValue('R1', 'gen');
        $objPHPExcel->getActiveSheet()->setCellValue('S1', 'numar_piese');
        $objPHPExcel->getActiveSheet()->setCellValue('T1', 'model');
        $objPHPExcel->getActiveSheet()->setCellValue('U1', 'licenta');
        $objPHPExcel->getActiveSheet()->setCellValue('V1', 'puzzle');
        $objPHPExcel->getActiveSheet()->setCellValue('W1', 'link_video');

        $i = 2;
        foreach ($products as $product) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $product['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $product['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $product['description_short']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $product['description_long']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $product['brand_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $product['category_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $product['subcategory_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $product['price']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $product['price_rec']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $product['stock_status']);

            $objPHPExcel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode('00.00');
            $objPHPExcel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode('00.00');

            $attributes_type = explode(',', $product['attribute_type']);
            $attributes_value = explode(',', $product['attribute_value']);

            if(count($attributes_type) == count($attributes_value)) {
                $attribut = array_combine($attributes_type, $attributes_value);

                foreach($attribut as $key => $value) {
                    if($key == 'dimensiune_produs') {
                        $attribute['product_dimension'] = explode('x', preg_replace('/\\s/', '', strtolower($value)));
                    } elseif ($key == 'dimensiune_cutie') {
                        $attribute['box_dimension'] = explode('x', preg_replace('/\\s/', '', strtolower($value)));
                    } else {
                        $attribute[$key] = $value;
                    }
                }
            }


            if(isset($attribute['box_dimension'][2])) {
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $attribute['box_dimension'][0]);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $attribute['box_dimension'][1]);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $attribute['box_dimension'][2]);
            }

            if(isset($attribute['product_dimension'][2])) {
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $attribute['product_dimension'][0]);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $attribute['product_dimension'][1]);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $attribute['product_dimension'][2]);
            }

            if(isset($attribute['varsta'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $attribute['varsta']);
            }

            if(isset($attribute['gen'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $attribute['gen']);
            }

            if(isset($attribute['piese'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $attribute['piese']);
            }

            if(isset($attribute['model'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $attribute['model']);
            }

            if(isset($attribute['licenta'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $attribute['licenta']);
            }

            if(isset($attribute['puzzle'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $attribute['puzzle']);
            }

            if(isset($attribute['link_video'])) {
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $attribute['link_video']);
            }

            $images = explode(',', $product['images']);
            $images_column = array('X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO');
            $j = 1;

            foreach($images_column as $key => $value) {
                $objPHPExcel->getActiveSheet()->setCellValue($value . '1', 'imagine' . $j);
                $j++;
            }

            foreach($images as $key => $value) {
                $objPHPExcel->getActiveSheet()->setCellValue($images_column[$key] . $i, $value);
            }

            $i++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,  'Excel5');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export feed produse ' . date('d-m-Y',  time()) . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');
        exit();
    }

}