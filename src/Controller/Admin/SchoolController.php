<?php

namespace App\Controller\Admin;

use App\ViewModel\School\SchoolTableViewModel;
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
    public function viewMainSchools(SchoolTableViewModel $schoolTableViewModel)
    {
        return $this->render('admin/page/school/main.html.twig', ['viewModel' => $schoolTableViewModel]);
    }
}
