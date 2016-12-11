<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Class For All Direct Class
 * User: huanle0610
 * Date: 2016/4/25
 * Time: 21:49
 */
class DirectBase
{
    protected $ci;
    /**
     * @var CI_DB_query_builder
     */
    protected $db;
    protected $mainTableAlias = 't';
    protected $mainTableAliasStr = 't.';
    protected $debug_sql = false;
    protected $_sql;
    protected $_select = '*';

    public function __construct()
    {
        $params = func_get_args();
        // Assign the CodeIgniter super-object
        $this->ci =& get_instance();

        if(isset($params[0]['need_db']) && $params[0]['need_db'] === false)
        {

        }
        else
        {
            $this->ci->load->database();
            $this->db = $this->ci->db;
        }
    }

    /**
     * @param string $select
     */
    public function setSelect($select)
    {
        $this->_select = $select;
    }

    protected function _applyConditions(&$params)
    {
        $sorts = isset($params->sort) ? $params->sort : null;
        $filters = isset($filter) ? $filter : null;

        if(isset($filters))
        {
            foreach($filters as $filter)
            {
                if(isset($filter->operator) && $filter->operator == '=')
                {
                    $this->db->where($this->mainTableAliasStr . $filter->property, $filter->value);
                }
                else
                {
                    $this->db->like($this->mainTableAliasStr . $filter->property, $filter->value);
                }
            }
        }

        if($sorts)
        {
            foreach($sorts as $sort)
            {
                $this->db->order_by($this->mainTableAliasStr . $sort->property, $sort->direction);
            }
        }

        $this->db->limit($params->limit, $params->start);
    }

    protected function _withCount($select = '*')
    {
         return 'SQL_CALC_FOUND_ROWS '. $select;
    }

    protected function _getTotalCount()
    {
        return $this->db->query('SELECT FOUND_ROWS() as _num')->row()->_num;
    }

    protected function doQueryList(&$params, $table, &$list, &$count)
    {
        // core db action
        // NO "SIDE" DB ACTION ALLOWED INSERT HERE!!!
        $this->_applyConditions($params);
        $this->db->select($this->_withCount($this->_select), false);

        $query = $this->db->get($table . ' ' .$this->mainTableAlias);
        if($this->debug_sql)
        {
            $this->_sql[] = $this->db->last_query();
        }
        // MUST LOCK INTO QUEUE
        $count = $this->_getTotalCount();
        // core db action end

        if ($query->num_rows() > 0)
        {
            $list = $query->result_array();
            return true;
        }

        return false;
    }

    /**
     * @param boolean $debug_sql
     */
    public function setDebugSql($debug_sql)
    {
        $this->debug_sql = $debug_sql;
    }

}