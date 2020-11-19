<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AwardCeremonyController extends AbstractController
{
    /**
     * @Route("/awards-ceremony", name="web_award_ceremony")
     */
    public function index()
    {
        return $this->render('web/page/award_ceremony/index.html.twig');
    }
}
