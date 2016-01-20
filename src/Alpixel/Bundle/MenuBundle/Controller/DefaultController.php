<?php

namespace Alpixel\Bundle\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AlpixelMenuBundle:Default:index.html.twig');
    }
}
