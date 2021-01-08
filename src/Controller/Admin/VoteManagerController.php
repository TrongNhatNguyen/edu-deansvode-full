<?php

namespace App\Controller\Admin;

use App\DTO\Request\VoteManager\CloseRequest;
use App\DTO\Request\VoteManager\StartRequest;
use App\Message\SmsMailStartCampaign;
use App\Util\Helper\MailHelper;

use App\Service\Admin\VoteManagerService;
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
    public function __construct(VoteManagerService $voteManagerService)
    {
        $this->voteManagerService = $voteManagerService;
    }


    /**
     * @route("/vote-manager", name="vote_manager", methods={"GET"})
     */
    public function index(Request $request)
    {
        $reqParams = $request->query->all();

        if (empty($reqParams)) {
            $allVoteSession = $this->voteManagerService->getAllVoteSession();
            $openingVoteSession = $this->voteManagerService->getOpeningVoteSession();

            return $this->render($this->voteManagerDir . 'index.html.twig', [
                'listVoteSession' => $allVoteSession,
                'openingVoteSession' => $openingVoteSession
            ]);
        }

        $listQuery = $this->voteManagerService->buildVoteSessionListQuery($reqParams);

        $html = $this->renderView($this->voteManagerPartialDir . 'list_vote_manager.html.twig', [
            'listVoteSession' => $this->voteManagerService->getListVoteSession($listQuery)
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
                'listVoteSession' => $this->voteManagerService->getAllVoteSession()
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
        if (isset($startRequest->startRequest)) {
            return $this->json(['status' => 'failed', 'messages' => $startRequest->errors]);
        }

        $result = $this->voteManagerService->createNewVoteSession($startRequest);

        if ($result['status'] === 'success') {
            // send mail all deans:
            if ($startRequest->checkSendMail === 1) {
                $deansInfo = $this->voteManagerService->getDeansInfo();

                $mailType = MailHelper::MAILER;
                $messageSendMailDeans = new SmsMailStartCampaign($deansInfo, $mailType);
                $this->dispatchMessage($messageSendMailDeans);

                $result['mailNotifi'] = 'Mail notification has been added to the task silently!';
            }

            $result['htmlForm'] = $this->renderView($this->voteManagerPartialDir . 'form_close_vote_manager.html.twig', [
                'openingVoteSession' => $this->voteManagerService->getOpeningVoteSession()
            ]);
            $result['htmlList'] = $this->renderView($this->voteManagerPartialDir . 'list_vote_manager.html.twig', [
                'listVoteSession' => $this->voteManagerService->getAllVoteSession()
            ]);
        }

        return $this->json($result);
    }
}
