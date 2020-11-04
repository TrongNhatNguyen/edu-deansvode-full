<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutUsController extends AbstractController
{
    /**
     * @Route("/about-eduniversal-dean-vote", name="web_about_us")
     */
    public function index()
    {
        return $this->render('web/page/about_us/index.html.twig');
    }
}
