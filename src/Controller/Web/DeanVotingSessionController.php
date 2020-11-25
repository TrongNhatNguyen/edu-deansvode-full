<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeanVotingSessionController extends AbstractController
{
    /**
     * @Route("/dean-voting-session", name="web_dean_voting_session")
     */
    public function index()
    {
        return $this->render('web/page/dean_voting_session/index.html.twig');
    }
}
