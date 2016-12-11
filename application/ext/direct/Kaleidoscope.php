<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Actor Api
 */
class Kaleidoscope Extends DirectBase{
    /**
	 * Constructor method
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

    /**
     * Actor list
     * @remotable
     */
    function getList($params)
    {
        $list = array();

        $this->ci->load->model('Dust_model', 'dust');
        $row = $this->ci->dust->get_by('dust_key', $params->key);
        if(!$row)
        {
            return array(
                'suc' => false,
                'msg' => 'dust does not exists.'
            );
        }

        $list = $this->db->query($row['sql_statement'])
                                ->result_array();

        $count = count($list);

        return array(
            'sql' => $row,
            'totalCount' => $count,
            'items' => $list
        );
    }
}
/* End of file */