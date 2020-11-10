<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StartYourVotingSessionController extends AbstractController
{
    /**
     * @Route("/voting-process/start-your-voting-session", name="web_start_your_voting_session")
     */
    public function index()
    {
        return $this->render('web/page/deans_session/start_your_voting_session.html.twig');
    }
}
