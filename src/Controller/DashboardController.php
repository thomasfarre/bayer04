<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/user", name="user_profile")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get user information from security token
        $user = $this->getUser();

        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($player);
            $entityManager->flush();

            return $this->redirectToRoute('player_index', [], Response::HTTP_SEE_OTHER);
        }

        // Pass user data to the view
        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'player' => $player,
            'form' => $form,
        ]);
    }
}
