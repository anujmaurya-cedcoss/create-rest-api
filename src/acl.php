<?php
namespace App\Acl;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Security\JWT\Token\Parser;

function checkAccess($token, $component, $action)
{
    $tokenReceived = $token;
    $parser = new Parser();

    $tokenObject = $parser->parse($tokenReceived);
    $role = $tokenObject->getClaims()->getPayload()['sub'];

    if ($role == '')
        $role = 'user';

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
