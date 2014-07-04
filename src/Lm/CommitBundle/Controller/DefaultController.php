<?php

namespace Lm\CommitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LmCommitBundle:Default:index.html.twig', array('name' => $name));
    }
}
