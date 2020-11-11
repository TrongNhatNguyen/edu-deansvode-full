<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VotingProcessController extends AbstractController
{
    /**
     * @Route("/voting-process/prepare-your-voting-session", name="web_prepare_your_voting_session")
     */
    public function prepareYourVotingSessionAction()
    {
        return $this->render('web/page/voting_process/prepare_your_voting_session.html.twig');
    }

    /**
     * @Route("/voting-process/start-your-voting-session", name="web_start_your_voting_session")
     */
    public function startYourVotingSessionAction()
    {
        return $this->render('web/page/voting_process/start_your_voting_session.html.twig');
    }

    /**
     * @Route("/voting-tutorial", name="web_voting_tutorial")
     */
    public function votingTutorialAction()
    {
        return $this->render('web/page/voting_process/voting_tutorial.html.twig');
    }
}
