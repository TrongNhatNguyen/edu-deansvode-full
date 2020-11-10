<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PrepareYourVotingSessionController extends AbstractController
{
    /**
     * @Route("/voting-process/prepare-your-voting-session", name="web_prepare_your_voting_session")
     */
    public function index()
    {
        return $this->render('web/page/deans_session/prepare_your_voting_session.html.twig');
    }
}
