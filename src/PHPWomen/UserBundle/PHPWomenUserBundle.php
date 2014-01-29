<?php

namespace PHPWomen\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PHPWomenUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
