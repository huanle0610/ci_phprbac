<?php
/**
 * RBAC Api
 */
class Rbac Extends DirectBase
{
    /**
     * @var PermissionManager
     */
    protected $permission;
    /**
     * @var \PhpRbac\Rbac
     */
    protected $rbac;
    /**
     * @var RoleManager
     */
    protected $role;

    /**
     * Constructor method
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->ci->load->library('RbacLib');

        $rbac = $this->ci->rbaclib->RBAC;
        $this->rbac = $rbac;
        $this->role = $rbac->Roles;
        $this->permission = $rbac->Permissions;
    }

    /**
     * add new role
     * @remotable
     */
    public function addRole($params)
    {
        $title = $params->path;
        $desc = $params->desc;

        $this->ci->load->database();

        $pid = $params->pid;

        $pathInfo = explode("\n", $title);
        $title = trim(array_shift($pathInfo));

        $desc = trim(implode("\n", $pathInfo));

        $this->role->add($title, $desc, $pid);
        return array(
            'suc' => true
        );
    }

    /**
     * check user has a permission or not
     * @remotable
     */
    public function hasPermission($params)
    {
        $path = $params->path;
        $uid = $params->uid;

        try{
            $res = $this->rbac->check($path, $uid);
        } catch (Exception $e){
            return array(
                'msg' => $e->getMessage(),
                'suc' => false
            );
        }

        return array(
            'suc' => $res
        );
    }

    /**
     * add new permission
     * @remotable
     */
    public function addPermission($params)
    {
        $title = $params->path;
        $desc = $params->desc;

        $this->ci->load->database();

        $pid = $params->pid;

        $pathInfo = explode("\n", $title);
        $title = trim(array_shift($pathInfo));

        $desc = trim(implode("\n", $pathInfo));

        $this->permission->add($title, $desc, $pid);
        return array(
            'suc' => true
        );
    }

    /**
     * reset items
     * @remotable
     */
    public function forceReset($params)
    {
        $type = $params->reset_type;

        $this->$type->reset(true);
        $this->$type->resetAssignments(true);

        return array(
            'suc' => true
        );
    }

    /**
     * @remotable
     */
    public function getRolePermissions($params)
    {
        $id = $params->id;
        $permissionTexts = array();

        $this->ci->load->helper('plantuml');

        $path = $this->role->getPath($id);
        $title = $this->role->getTitle($id);
        $permissions = $this->role->permissions($id, false);
        $roleDesc = $this->role->getDescription($id);
        $hasPermission = !!$permissions;
        if($hasPermission) {
            foreach($permissions as $row)
            {
                $permissionTexts[] = getChartTitleText($row);
            }
        }

        return array(
            'path' => $path,
            'title' => $title,
            'hasPermission' => $hasPermission,
            'permissionTexts' => $permissionTexts,
            'roleDesc' => $roleDesc,
            'permissions' => $permissions,
            'suc' => true
        );
    }

    /**
     * @remotable
     */
    public function getPermissionPath($params)
    {
        $id = $params->id;

        $path = $this->permission->getPath($id);
        $title = $this->permission->getTitle($id);
        $permDesc = $this->permission->getDescription($id);
        return array(
            'path' => $path,
            'permDesc' => $permDesc,
            'title' => $title,
            'suc' => true
        );
    }

    /**
     * @remotable
     */
    public function checkRoleHasPerm($params)
    {
        $rolePath = $params->rolePath;
        $permPath = $params->permPath;

        $role = $this->role->pathId($rolePath);
        $perm = $this->permission->pathId($permPath);

        return array(
            'hasPerm' => $this->role->hasPermission($role, $perm),
            'suc' => true
        );
    }

    /**
     * bind role and permission
     * @remotable
     */
    public function assign($params)
    {
        $role = $params->rolePath;
        $permission = $params->permPath;

        $this->rbac->assign( $role, $permission );

        return array(
            'msg' => 'bind successful',
            'suc' => true
        );
    }

    /**
     * unbind role and permission
     * @remotable
     */
    public function unassign($params)
    {
        $role = $params->rolePath;
        $permission = $params->permPath;

        $res = $this->role->unassign( $role, $permission );

        return array(
            'msg' => $res ? 'unbind successful' : 'unbind failed',
            'suc' => $res
        );
    }
}