<?php

namespace App\Controller\Web;

use App\Message\SmsNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test")
     *
     * @return void
     */
    public function index()
    {
        return $this->render('web/page/test/index.html.twig');
    }

    /**
     * @Route("test/messenger", name="test_messenger")
     *
     * @return void
     */
    public function testMessenger()
    {
        // $message = new SmsNotification('test messenger');

        // $this->dispatchMessage($message);

        // return new Response('Done');
    }
}
