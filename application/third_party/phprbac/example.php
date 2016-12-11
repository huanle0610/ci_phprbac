<?php
require_once 'autoload.php';
require_once 'src/PhpRbac/Rbac.php';
$rbac = new \PhpRbac\Rbac();

function getPermissionUml(&$rbac)
{
    $arr = Jf::sql('select * from phprbac_permissions');
    echo "title Permissions", "\n";
    foreach($arr as $row)
    {
        $p = $rbac->Permissions->parentNode($row['ID']);

        $title = getChartTitle($row);

        $itemType = (($row['Rght'] - $row['Lft']) === 1) ? 'entity' : 'storage';

        echo $itemType, " \"", $row['Title'], " ", $row['ID'], "\" as ",$title, "\n";
        if($p)
        {
            $parent_title = getChartTitle($p);
            echo $title, " -u-> ", $parent_title , "\n";
        }
    }
}

function getRoleUml(&$rbac)
{
    $arr = Jf::sql('select * from phprbac_roles');
    echo "title Roles", "\n";
    foreach($arr as $row)
    {
        $p = $rbac->Roles->parentNode($row['ID']);

        $title = getChartTitle($row);

        $itemType = (($row['Rght'] - $row['Lft']) === 1) ? 'entity' : 'storage';

        echo $itemType, " \"", $row['Title'], "\" as ",$title, "\n";
        if($p)
        {
            $parent_title = getChartTitle($p);
            echo $parent_title," -d-> ",$title , "\n";
        }
    }
}

function getChartTitle($row)
{
    $title = str_replace(" ", '_', $row['Title']);
    return sprintf("%s_%d", $title, $row['ID']);
}

getPermissionUml($rbac);
//getRoleUml($rbac);


//$p = $rbac->Permissions->parentNode(9);
//var_dump($p);
echo file_get_contents('http://localhost:8080/plantuml/svg/VP91Rnen48Nlyoj6t2DXiv1mgHIbJKvHK4Xx7LamILPQxCWsaVZttVNOtguAdEOzhx_78B_Onevnm_xGXj0w6o14vyq7uymx5r7Dq0GS9AdnqxEpV4ATlYLD6lB4c-FDZmAmZMqyuUp5xzdZ8jrFcXQGfzml8GLuyyQ6zvvf4bCiDJ3AALbtsvv2v0Ck4oY6bb34PBtAl3ft2BXAIDAqWZm5g3bbmItxxtwzM_cLPEWMYX2e91NcZpbs4UyIaZJTGPvJeExrEx0Fk4v4qhI6F2_iTR_Vevede9UavY13hfUCSllNlksVMDvuqAGKv7aUs9WGyE7Js7ux9Uy_794SaT8mjUTJNldWlli0AOGuKWkeQegDpyHVpiU0gibWigGQ6ENP_ZsnPMywLDAAR4ajOPJv2SRC0Z-srjXTE-3Ia7f0QWN_ceiHMzuT_Q1KAOnuKaDdglwFh__yZNUVrdNkeu-GEemdfDOmSHVNsFZMHjHIZsH8pw48mQQvXNmsROTQYZ9eqWhol8ODRTIwjb5ha76D-SaTHqOjvH17UW5LNODJeNKp5betaESPx2y0');
die();

$perm_id = $rbac->Permissions->returnId('delete_posts');
//echo $perm_id;
//
// Get Role Id
$role_id = $rbac->Roles->returnId('aforum_moderator');
var_dump($role_id);
var_dump($rbac->check('delete_posts', 2));



var_dump($rbac->Roles->children(12));
var_dump($rbac->Roles->descendants(12));
var_dump($rbac->Roles->depth(1));
var_dump($rbac->Roles->depth(12));
var_dump($rbac->Roles->depth(13));
var_dump($rbac->Roles->depth(14));
var_dump($rbac->Roles->getPath(12));
var_dump($rbac->Roles->getPath(14));
var_dump($rbac->Roles->getPath(1));
var_dump($rbac->Roles->permissions(1));
var_dump($rbac->Users->allRoles(1));
die();

// Create a Permission
$perm_id = $rbac->Permissions->add('delete_posts', 'Can delete forum posts');

// Create a Role
$role_id = $rbac->Roles->add('forum_moderator', 'User can moderate forums');


$perm_descriptions = array(
    'Can delete users',
    'Can edit user profiles',
    'Can view users'
);

$rbac->Permissions->addPath('/delete_users/edit_users/view_users', $perm_descriptions);

$role_descriptions = array(
    'Forum Administrator',
    'Forum Moderator',
    'Registered Forum Member'
);

$rbac->Roles->addPath('/admin/forum_moderator/forum_member', $role_descriptions);
