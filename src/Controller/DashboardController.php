<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Role;
use App\Form\PlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_profile')]
class DashboardController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function index(Request $request): Response
    {
        $user = $this->getUser();

        $roles = $this->findAllRoles();
        $rolesArray = array_map(fn($role) => $role->serializeRole(), $roles);


        [$player, $form] = $this->handlePlayerCreation($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'player' => $player,
            'form' => $form,
            'roles' => $rolesArray
        ]);
    }

    private function findAllRoles(): array
    {
        return $this->entityManager->getRepository(Role::class)->findAll();
    }


    private function handlePlayerCreation(Request $request): array
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($player);
            $this->entityManager->flush();
        }

        return [$player, $form];
    }
}
