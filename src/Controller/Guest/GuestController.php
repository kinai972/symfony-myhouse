<?php

namespace App\Controller\Guest;

use App\Repository\SliderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/', name: 'app_guest_guest_', methods: [Request::METHOD_GET])]
class GuestController extends AbstractController
{
    #[Route('', name: 'home')]
    public function home(SliderRepository $sliderRepository): Response
    {
        return $this->render(view: 'guest/guest/home.html.twig', parameters: [
            'images' => $sliderRepository->findBy(criteria: [], orderBy: ['place' => 'ASC']),
        ]);
    }

    #[Route('/hotel', name: 'hotel')]
    public function hotel(): Response
    {
        return $this->render(view: 'guest/guest/hotel.html.twig', parameters: [
            'title' => "À propos de notre hôtel",
        ]);
    }
}
