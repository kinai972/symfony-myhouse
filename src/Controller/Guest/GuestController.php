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

    #[Route('/actualites', name: 'news')]
    public function news(): Response
    {
        return $this->render(view: 'guest/guest/news.html.twig', parameters: [
            'title' => "Les actualités du secteur hôtelier",
        ]);
    }

    #[Route('/mentions-legales', name: 'legalNotice')]
    public function legalNotice(): Response
    {
        return $this->render(view: 'guest/guest/legal_notice.html.twig', parameters: [
            'title' => "Les mentions légales",
        ]);
    }

    #[Route('/conditions-générales', name: 'termsAndConditions')]
    public function termsAndConditions(): Response
    {
        return $this->render(view: 'guest/guest/terms_and_conditions.html.twig', parameters: [
            'title' => "Conditions générales",
        ]);
    }

    #[Route('/plan-du-site', name: 'plan')]
    public function plan(): Response
    {
        return $this->render(view: 'guest/guest/plan.html.twig', parameters: [
            'title' => "Plan du site",
        ]);
    }
}
