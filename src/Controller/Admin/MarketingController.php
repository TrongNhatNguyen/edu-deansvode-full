<?php

namespace App\Controller\Admin;

use App\DTO\Request\EmailTemplate\CreateRequest;
use App\DTO\Request\EmailTemplate\LoadTempRequest;
use App\DTO\Request\EmailTemplate\SendTestRequest;
use App\DTO\Request\EmailTemplate\UpdateRequest;
use App\Service\EmailTemplate\EmailTemplateFetcher;
use App\Service\MarketingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class MarketingController extends AbstractController
{
    public $marketingDir = 'admin/page/marketing/';
    public $marketingPatialDir = 'admin/page/marketing/partial/';

    private $emailTemplateFetcher;
    private $marketingService;

    public function __construct(
        EmailTemplateFetcher $emailTemplateFetcher,
        MarketingService $marketingService
    ) {
        $this->emailTemplateFetcher = $emailTemplateFetcher;
        $this->marketingService = $marketingService;
    }


    /**
     * @Route("/new-marketing", name="new_marketing")
     */
    public function index(Request $request)
    {
        $request = $request->query->all();

        if (empty($request)) {
            return $this->render($this->marketingDir . 'new_marketing_index.html.twig', [
                'listEmailTemplate' => $this->emailTemplateFetcher->getAllEmailTemplates(),
                'activeEmailtemplate' => $this->emailTemplateFetcher->getEmailTemplateActive()
            ]);
        }

        return $this->json([
            'status' => 'success',
            'html' => $this->renderView($this->marketingPatialDir . 'list_templates.html.twig', [
                'listEmailTemplate' => $this->marketingService->listEmailTemplate($request)
            ])
        ]);
    }

    /**
     * @Route("/#create-new-template", name="create_email_template")
     */
    public function create(CreateRequest $createRequest)
    {
        // validation:
        if (isset($createRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $createRequest->errors]);
        }

        $result = $this->marketingService->createEmailTemplate($createRequest);

        if ($result['status'] === 'success') {
            $result['html'] = $this->renderView($this->marketingPatialDir . 'list_templates.html.twig', [
                'listEmailTemplate' => $this->emailTemplateFetcher->getAllEmailTemplates()
            ]);
        }

        return $this->json($result);
    }

    /**
     * @Route("/#load-template", name="load_email_template")
     */
    public function loadTemplate(LoadTempRequest $loadTempRequest)
    {
        // validation:
        if (isset($loadTempRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $loadTempRequest->errors]);
        }

        $this->marketingService->loadEmailTemplate($loadTempRequest);

        return $this->json([
            'emailTemplate' => $this->emailTemplateFetcher->getEmailTemplateById($loadTempRequest->id),
            'html' => $this->renderView($this->marketingPatialDir . 'list_templates.html.twig', [
                'listEmailTemplate' => $this->emailTemplateFetcher->getAllEmailTemplates()
            ])
        ]);
    }

    /**
     * @Route("/#update-template", name="update_email_template")
     */
    public function update(UpdateRequest $updateRequest)
    {
        // validation:
        if (isset($updateRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $updateRequest->errors]);
        }

        $result = $this->marketingService->updateEmailTemplate($updateRequest);

        if ($result['status'] === 'success') {
            $result['html'] = $this->renderView($this->marketingPatialDir . 'list_templates.html.twig', [
                'listEmailTemplate' => $this->emailTemplateFetcher->getAllEmailTemplates()
            ]);
        }

        return $this->json($result);
    }

    /**
     * @Route("/#send-mail-test", name="send_mail_test")
     */
    public function sendMailTest(SendTestRequest $sendTestRequest)
    {
        // validation:
        if (isset($sendTestRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $sendTestRequest->errors]);
        }

        $result = $this->marketingService->sendMailTest($sendTestRequest);

        return $this->json($result);
    }
}
