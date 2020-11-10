<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InternationalScientificCommitteeController extends AbstractController
{
    /**
     * @Route("/methodology/international-scientific-committee", name="web_international_scientific_committee")
     */
    public function index()
    {
        return $this->render('web/page/methodology/international_scientific_committee.html.twig');
    }
}