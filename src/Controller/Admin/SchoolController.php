<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class SchoolController extends AbstractController
{
    /**
     * @Route("/school")
     */
    public function viewMainSchool()
    {
        return $this->render('admin/page/school/main.html.twig');
    }
}
