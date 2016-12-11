<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RBAC Class
 * Date: 2016/5/1
 * Time: 10:43
 */
class RbacLib
{
    protected $ci;
    /**
     * @var CI_DB_query_builder
     */
    protected $db;
    public $RBAC;

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

        require_once APPPATH.'third_party/phprbac/autoload.php';
        require_once APPPATH.'third_party/phprbac/src/PhpRbac/Rbac.php';
        $this->RBAC = new \PhpRbac\Rbac();

        log_message('error', 'load '. __CLASS__);
    }

    function  getChartTitle($row)
    {
        $title = str_replace(" ", '_', $row['Title']);
        return sprintf("%s_%d", $title, $row['ID']);
    }

    function getPermissionUml($permission_id)
    {
        if($permission_id)
        {
            $arr = $this->RBAC->Permissions->descendants($permission_id);
        }
        else
        {
            $arr = Jf::sql('select * from phprbac_permissions');
        }

        $uml = "title Permissions" . "\n";

        if(!$arr)
        {
            $row = array('Title' => $this->RBAC->Permissions->getTitle($permission_id), 'ID' => $permission_id);
            $title = getChartTitle($row);
            $uml =  'usecase'  . " \"" . $row['Title'] . " #" . $permission_id . "\" as " .$title . "\n";
            return $uml;
        }

        foreach($arr as $row)
        {
            $p = $this->RBAC->Permissions->parentNode($row['ID']);

            $title =  getChartTitle($row);

            $itemType = (($row['Rght'] - $row['Lft']) === 1) ? 'entity' : 'storage';

            $uml .= $itemType . " \"" . $row['Title'] . " #" . $row['ID']. sprintf(" [%d:%d]", $row['Lft'], $row['Rght']) . "\" as " .$title . "\n";
            if($p)
            {
                $parent_title =  getChartTitle($p);
                $uml .= $title . " -u-> " . $parent_title  . "\n";
            }
        }

        return $uml;
    }

    function getRoleUml($role_id = null)
    {
        if($role_id)
        {
            $arr = $this->RBAC->Roles->descendants($role_id);
        }
        else
        {
            $arr = Jf::sql('select * from phprbac_roles');
        }
        $uml = "title Roles" . "\n";
        if(!$arr)
        {
            $row = array('Title' => $this->RBAC->Permissions->getTitle($role_id), 'ID' => $role_id);
            $title = getChartTitle($row);
            $uml =  'usecase'  . " \"" . $row['Title'] . " #" . $role_id . "\" as " .$title . "\n";
            return $uml;
        }

        foreach($arr as $row)
        {
            $p = $this->RBAC->Roles->parentNode($row['ID']);

            $title =  getChartTitle($row);

            $itemType = (($row['Rght'] - $row['Lft']) === 1) ? 'entity' : 'storage';

            $uml .= $itemType . " \"" . $row['Title'] . " #" . $row['ID'] . sprintf(" [%d:%d]", $row['Lft'], $row['Rght']) ."\" as " .$title . "\n";
            if($p)
            {
                $parent_title =  getChartTitle($p);
                $uml .= $parent_title ." -d-> " .$title  . "\n";
            }
        }

        return $uml;
    }

}