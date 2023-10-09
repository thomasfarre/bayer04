<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Role;
use App\Form\PlayerType;
use App\Form\RoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;


#[Route('/user', name: 'user_profile')]
class DashboardController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function index(Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $user = $this->getUser();

        $roles = $this->findAllRoles();
        $rolesArray = array_map(fn($role) => $role->serializeRole(), $roles);

        [$player, $playerForm] = $this->handlePlayerCreation($request);
        [$role, $roleForm] = $this->handleRoleCreation($request);

        if ($playerForm->isSubmitted() && $playerForm->isValid()) {
            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }

        if ($roleForm->isSubmitted() && $roleForm->isValid()) {
            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'player' => $player,
            'role' => $role,
            'playerForm' => $playerForm,
            'roleForm' => $roleForm,
            'roles' => $rolesArray,
        ]);
    }


    private function findAllRoles(): array
    {
        return $this->entityManager->getRepository(Role::class)->findAll();
    }

    private function handleRoleCreation(Request $request): array
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($role);
            $this->entityManager->flush();
        }

        return [$role, $form];
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
