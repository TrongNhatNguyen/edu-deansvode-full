<?php

namespace App\Controller\Admin;

use App\Message\SmsMailStartCampaign;
use App\Service\Admin\VoteManagerService;
use App\Util\Helper\MailHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @route("/admin", name="admin_")
 */
class VoteManagerController extends AbstractController
{
    private $voteManagerService;

    public function __construct(VoteManagerService $voteManagerService)
    {
        $this->voteManagerService = $voteManagerService;
    }


    /**
     * @route("/vote-manager", name="vote_manager")
     */
    public function index(Request $request)
    {
        $reqParams = $request->query->all();
        if (empty($reqParams)) {
            $allVoteSession = $this->voteManagerService->getAllVoteSession();
            $openingVoteSession = $this->voteManagerService->getOpeningVoteSession();

            return $this->render('admin/page/vote_manager/index.html.twig', [
                'listVoteSession' => $allVoteSession,
                'openingVoteSession' => $openingVoteSession
            ]);
        }

        $newList = $this->voteManagerService->getListVoteSession($reqParams);

        $html = $this->renderView('admin/page/vote_manager/partial/list_vote_manager.html.twig', [
            'listVoteSession' => $newList
        ]);

        return $this->json(['status' => 'success', 'html' => $html]);
    }

    /**
     * @route("/#close-vote-session", name="close_vote_session_action")
     */
    public function closeVoteSession(Request $request)
    {
        $voteSessionId = $request->request->get('vote_session_id');
        $result = $this->voteManagerService->updateVoteSession($voteSessionId);
        $allVoteSession = $this->voteManagerService->getAllVoteSession();
        
        if ($result['status'] === 'success') {
            $result['htmlForm'] = $this->renderView('admin/page/vote_manager/partial/form_start_new_vote_manager.html.twig');
            $result['htmlList'] = $this->renderView('admin/page/vote_manager/partial/list_vote_manager.html.twig', [
                'listVoteSession' => $allVoteSession
            ]);
        }

        return $this->json($result);
    }

    /**
     * @route("/#start-new-vote-session", name="start_new_vote_session_action")
     */
    public function startNewVoteSession(Request $request)
    {
        $data = $request->request->all();

        $result = $this->voteManagerService->createNewVoteSession($data);
        $allVoteSession = $this->voteManagerService->getAllVoteSession();
        $openingVoteSession = $this->voteManagerService->getOpeningVoteSession();

        if ($result['status'] === 'success') {
            // send mail all deans:
            if (!empty($data['isSendMail']) && $data['isSendMail'] === "on") {
                $deansInfo = $this->voteManagerService->getDeansInfo();

                $mailType = MailHelper::MAILER;
                $messageSendMailDeans = new SmsMailStartCampaign($deansInfo, $mailType);
                $this->dispatchMessage($messageSendMailDeans);

                $result['mailNotifi'] = 'Mail notification has been added to the task silently!';
            }

            $result['htmlForm'] = $this->renderView('admin/page/vote_manager/partial/form_close_vote_manager.html.twig', [
                'openingVoteSession' => $openingVoteSession
            ]);
            $result['htmlList'] = $this->renderView('admin/page/vote_manager/partial/list_vote_manager.html.twig', [
                'listVoteSession' => $allVoteSession
            ]);
        }

        return $this->json($result);
    }
}
