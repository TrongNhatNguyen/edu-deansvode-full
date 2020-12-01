<?php

namespace App\Controller\Dean;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VotingSessionController extends AbstractController
{
    /**
     * @Route("/voting-session/finish", name="dean_voting_session")
     */
    public function index()
    {
        return $this->render('web/page/dean/voting_session.html.twig');
    }
}
