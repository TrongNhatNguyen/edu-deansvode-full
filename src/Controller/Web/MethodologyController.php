<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MethodologyController extends AbstractController
{
    /**
     * @Route("/methodology/international-scientific-committee", name="web_international_scientific_committee")
     */
    public function internationalScientificCommittee()
    {
        return $this->render('web/page/methodology/international_scientific_committee.html.twig');
    }

    /**
     * @Route("/methodology/eduniversal-evaluation-system", name="web_eduniversal_evaluation_system")
     */
    public function eduniversalEvaluationSystem()
    {
        return $this->render('web/page/methodology/eduniversal_evaluation_system.html.twig');
    }

    /**
     * @Route("/methodology/official-selection-of-the1000-best-business-schools", name="web_official_selection_of_the1000_best_business_schools")
     */
    public function officialSelectionOfThe1000BestBusinessSchools()
    {
        return $this->render('web/page/methodology/official_selection_of_the1000_best_business_schools.html.twig');
    }

    /**
     * @Route("/methodology/palmes-of-excellence", name="web_palmes_of_excellence")
     */
    public function palmesOfExcellence()
    {
        return $this->render('web/page/methodology/palmes_of_excellence.html.twig');
    }

    /**
     * @Route("/methodology/entering-the-system-obtaining-an-additional-palme", name="web_entering_the_system_obtaining_an_additional_palme")
     */
    public function enteringTheSystemObtainingAnAdditionalPalme()
    {
        return $this->render('web/page/methodology/entering_the_system_obtaining_an_additional_palme.html.twig');
    }
}
