<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/user", name="user_profile")
     */
    public function index()
    {
        // Get user information from security token
        $user = $this->getUser();

        // Pass user data to the view
        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
        ]);
    }
}
