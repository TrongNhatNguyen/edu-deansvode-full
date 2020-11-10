<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VotingTutorialController extends AbstractController
{
    /**
     * @Route("/voting-tutorial", name="web_voting_tutorial")
     */
    public function index()
    {
        return $this->render('web/page/deans_session/voting_tutorial.html.twig');
    }
}
