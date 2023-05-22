<?php
namespace App\Acl;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;

function checkAccess($role, $component, $action)
{
    if($role == '') $role = 'user';
    
    $acl = new Memory();

    $acl->addRole('admin');
    $acl->addRole('user');

    $acl->addComponent(
        'products',
        [
            'search',
            'get',
        ]
    );

    $acl->allow('admin', '*', '*');
    $acl->allow('user', 'products', 'search');

    return $acl->isAllowed("$role", "$component", "$action");
}
