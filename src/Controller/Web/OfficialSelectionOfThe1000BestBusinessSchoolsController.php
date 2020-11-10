<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OfficialSelectionOfThe1000BestBusinessSchoolsController extends AbstractController
{
    /**
     * @Route("/methodology/official-selection-of-the1000-best-business-schools", name="web_official_selection_of_the1000_best_business_schools")
     */
    public function index()
    {
        return $this->render('web/page/methodology/official_selection_of_the1000_best_business_schools.html.twig');
    }
}
