<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="web_home")
     */
    public function index()
    {
        return $this->render('web/page/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
