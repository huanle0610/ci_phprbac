<?php
class Film_model extends MY_Model
{
    public $_table = 'film';
    public $primary_key = 'film_id';


    public function getFilmInStock($film_id, $store_id, &$count)
    {
        $query = $this->db->query('call film_in_stock(?, ?, @film_count)', array($film_id, $store_id));

        $data = $query->result_array();

        while($query->next_result())
        {
            var_dump($query->result_array());
        }
        $count = $this->db->query("SELECT @film_count as _num")->row()->_num;

        return $data;
    }
}