<?php
namespace User\Token;

use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;

function generateToken($role)
{
    // Defaults to 'sha512'
    $signer = new Hmac();

    // Builder object
    $builder = new Builder($signer);

    $now = new \DateTimeImmutable();
    $issued = $now->getTimestamp();
    $notBefore = $now->modify('-1 minute')->getTimestamp();
    $expires = $now->modify('+1 day')->getTimestamp();
    $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

    // Setup
    $builder
        ->setExpirationTime($expires) // exp
        ->setIssuedAt($issued) // iat
        ->setIssuer('https://phalcon.io') // iss
        ->setNotBefore($notBefore) // nbf
        ->setSubject($role) // sub
        ->setPassphrase($passphrase) // password
    ;

    // Phalcon\Security\JWT\Token\Token object
    $tokenObject = $builder->getToken();

    // The token
    return $tokenObject->getToken();
}
