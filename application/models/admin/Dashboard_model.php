<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_count_record($table, $params = null) {
        if(isset($params)) {
            $this->db->where($params);
        }

        $this->db->from($table);

        return $this->db->count_all_results();
    }

    public function stock_pieces() {
	    $this->db->select_sum('stock')->where('stock >', '0');

	    $query = $this->db->get('products');

	    return $query->row()->stock;
    }

}
