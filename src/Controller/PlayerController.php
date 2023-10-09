<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

#[Route('/player')]
class PlayerController extends AbstractController
{
    #[Route('/{id}', name: 'app_player_delete', methods: ['POST'])]
    public function delete(Request $request, Player $player, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        try {
            $entityManager->remove($player);
            $entityManager->flush();

            $logger->info('Player deleted successfully.', ['playerId' => $player->getId()]);

            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
        } catch (\Exception $e) {
            $logger->error('Error deleting player.', ['playerId' => $player->getId(), 'error' => $e->getMessage()]);

            return new JsonResponse(['status' => 'error', 'message' => 'An error occurred while trying to delete the player.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}



