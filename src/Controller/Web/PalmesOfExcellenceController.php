<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PalmesOfExcellenceController extends AbstractController
{
    /**
     * @Route("/methodology/palmes-of-excellence", name="web_palmes_of_excellence")
     */
    public function index()
    {
        return $this->render('web/page/methodology/palmes_of_excellence.html.twig');
    }
}
