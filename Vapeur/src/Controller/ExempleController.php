<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class ExempleController extends AbstractController
{
    /**
     * @Route("/")
     **/
    public function index() {
        return $this->render('login.html.twig',
            ['title' => 'Vapeur™']);

    }
}
