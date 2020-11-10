<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EduniversalEvaluationSystemController extends AbstractController
{
    /**
     * @Route("/methodology/eduniversal-evaluation-system", name="web_eduniversal_evaluation_system")
     */
    public function index()
    {
        return $this->render('web/page/methodology/eduniversal_evaluation_system.html.twig');
    }
}
