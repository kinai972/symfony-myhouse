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

    #[Route('/restaurant', name: 'restaurant')]
    public function restaurant(): Response
    {
        return $this->render(view: 'guest/guest/restaurant.html.twig', parameters: [
            'title' => "À propos de notre restaurant",
        ]);
    }

    #[Route('/spa', name: 'spa')]
    public function spa(): Response
    {
        return $this->render(view: 'guest/guest/spa.html.twig', parameters: [
            'title' => "À propos de notre spa",
        ]);
    }

    #[Route('/temoignages', name: 'testimonials')]
    public function testimonials(): Response
    {
        return $this->render(view: 'guest/guest/testimonials.html.twig', parameters: [
            'title' => "Vos témoignages",
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
