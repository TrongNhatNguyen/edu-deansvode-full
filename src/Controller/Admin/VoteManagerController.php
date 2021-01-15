<?php

namespace App\Controller\Admin;

use App\DTO\Request\VoteManager\CloseRequest;
use App\DTO\Request\VoteManager\StartRequest;

use App\Service\VoteManagerService;
use App\Message\SmsMailStartCampaign;
use App\Service\Dean\DeanQueryBuilder;
use App\Service\VoteManager\VoteSessionFetcher;
use App\Util\Helper\MailHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @route("/admin", name="admin_")
 */
class VoteManagerController extends AbstractController
{
    public $voteManagerDir = 'admin/page/vote_manager/';
    public $voteManagerPartialDir = 'admin/page/vote_manager/partial/';

    private $voteManagerService;
    private $voteSessionFetcher;

    public function __construct(
        VoteManagerService $voteManagerService,
        VoteSessionFetcher $voteSessionFetcher
    ) {
        $this->voteManagerService = $voteManagerService;
        $this->voteSessionFetcher = $voteSessionFetcher;
    }


    /**
     * @route("/vote-manager", name="vote_manager", methods={"GET"})
     */
    public function index(Request $request)
    {
        $request = $request->query->all();

        if (empty($request)) {
            return $this->render($this->voteManagerDir . 'index.html.twig', [
                'listVoteSession' => $this->voteSessionFetcher->listAllVoteSession(),
                'openingVoteSession' => $this->voteSessionFetcher->openingVoteSession()
            ]);
        }

        $html = $this->renderView($this->voteManagerPartialDir . 'list_vote_manager.html.twig', [
            'listVoteSession' => $this->voteManagerService->listVoteSession($request)
        ]);

        return $this->json(['status' => 'success', 'html' => $html]);
    }

    /**
     * @route("/#close-vote-session", name="close_vote_session_action")
     */
    public function close(CloseRequest $closeRequest)
    {
        // validation:
        if (isset($closeRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $closeRequest->errors]);
        }

        $result = $this->voteManagerService->updateVoteSession($closeRequest);
        
        if ($result['status'] === 'success') {
            $result['htmlForm'] = $this->renderView($this->voteManagerPartialDir . 'form_start_new_vote_manager.html.twig');
            $result['htmlList'] = $this->renderView($this->voteManagerPartialDir . 'list_vote_manager.html.twig', [
                'listVoteSession' => $this->voteSessionFetcher->listAllVoteSession()
            ]);
        }

        return $this->json($result);
    }

    /**
     * @route("/#start-new-vote-session", name="start_new_vote_session_action")
     */
    public function create(StartRequest $startRequest)
    {
        // validation:
        if (isset($startRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $startRequest->errors]);
        }

        $result = $this->voteManagerService->createNewVoteSession($startRequest);

        $result['htmlForm'] = $this->renderView($this->voteManagerPartialDir . 'form_close_vote_manager.html.twig', [
            'openingVoteSession' => $this->voteSessionFetcher->openingVoteSession()
        ]);
        $result['htmlList'] = $this->renderView($this->voteManagerPartialDir . 'list_vote_manager.html.twig', [
            'listVoteSession' => $this->voteSessionFetcher->listAllVoteSession()
        ]);

        return $this->json($result);
    }
}
