<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EnteringTheSystemObtainingAnAdditionalPalmeController extends AbstractController
{
    /**
     * @Route("/methodology/entering-the-system-obtaining-an-additional-palme", name="web_entering_the_system_obtaining_an_additional_palme")
     */
    public function index()
    {
        return $this->render('web/page/methodology/entering_the_system_obtaining_an_additional_palme.html.twig');
    }
}
