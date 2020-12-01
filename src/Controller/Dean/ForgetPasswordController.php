<?php

namespace App\Controller\Dean;

use App\DTO\Request\EmailRequest;
use App\Message\SmsUserConfirm;
use App\Security\Dean\UpdatePassword;
use App\Util\Helper\MailHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ForgetPasswordController extends AbstractController
{
    /**
     * @Route("/foget-password", name="dean_foget_password")
     */
    public function showForgetPassword()
    {
        return $this->render('web/page/dean/forget_password.html.twig');
    }

    /**
     * @route("/#update-password", name="dean_update_password")
     */
    public function updatePassword(
        Request $request,
        EmailRequest $emailRequest,
        ValidatorInterface $validator,
        UpdatePassword $updatePassword
    ) {
        // validate email:
        $emailRequest->buildByRequest($request);
        $error = $validator->validate($emailRequest);

        if (count($error) > 0) {
            $messages = [];
            foreach ($error as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }

            return $this->json([
                'notificate' => ['status' => 'failed', 'error' => $messages]
            ]);
        }

        // go to update:
        $result = $updatePassword->updatePassword($request->request->get('email'));
        
        // mail confirm:
        $userConfirm = $result['dataUser'];
        $mailType = MailHelper::MAILER;
        $messageConfirm = new SmsUserConfirm($userConfirm, $mailType);
        $this->dispatchMessage($messageConfirm);

        return $this->json([
            'notificate' => $result
        ]);
    }
}
