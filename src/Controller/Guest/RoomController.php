<?php

namespace App\Controller\Guest;

use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RoomController extends AbstractController
{
    #[Route('/nos-chambres', name: 'app_guest_room_index')]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render(view: 'guest/room/index.html.twig', parameters: [
            'title' => "Nos chambres",
            'rooms' => $roomRepository->findAll(),
        ]);
    }
}
