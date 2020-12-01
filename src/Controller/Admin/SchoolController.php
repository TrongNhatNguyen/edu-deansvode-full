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
     * @Route("/schools", name="admin_school_list")
     */
    public function viewMainSchools()
    {
        return $this->render('admin/page/school/main.html.twig');
    }
}
