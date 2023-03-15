<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Entity\Booking;
use App\Form\Admin\BookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: 'admin/commandes', name: 'app_admin_booking_')]
class BookingController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: [Request::METHOD_GET])]
    public function index(BookingRepository $repository): Response
    {
        return $this->render(view: 'admin/booking/index.html.twig', parameters: [
            'bookings' => $repository->findBy(criteria: [], orderBy: ['registeredAt' => 'DESC']),
        ]);
    }

    #[Route('/creer', name: 'create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: BookingType::class, data: $booking = new Booking(), options: [
            'validation_groups' => [Constraint::DEFAULT_GROUP, 'admin']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Room */
            $room = $booking->getRoom();

            $bookingDuration =
                $booking
                ->getStartsAt()
                ->diff($booking->getEndsAt())
                ->days;

            $booking->setTotal($bookingDuration * $room->getNight());

            $booking->setRoomReference($room->getId() . ' - ' . $room->getTitle());

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "La commande n°{$booking->getId()} a été créée avec succès."
            );

            return $this->redirectToRoute(route: 'app_admin_booking_index');
        }

        return $this->render(view: 'admin/booking/create.html.twig', parameters: [
            'title' => "BACKOFFICE | Création d'une commande",
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Booking $booking, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: BookingType::class, data: $booking, options: [
            'validation_groups' => [Constraint::DEFAULT_GROUP, 'admin']
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Room */
            $room = $booking->getRoom();

            $bookingDuration =
                $booking
                ->getStartsAt()
                ->diff($booking->getEndsAt())
                ->days;

            $booking->setTotal($bookingDuration * $room->getNight());

            $booking->setRoomReference($room->getId() . ' - ' . $room->getTitle());

            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "La commande n°{$booking->getId()} a été modifiée avec succès."
            );

            return $this->redirectToRoute(route: 'app_admin_booking_index');
        }

        return $this->render(view: 'admin/booking/update.html.twig', parameters: [
            'title' => "BACKOFFICE | Modification d'une commande",
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, Booking $booking, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
            $manager->remove($booking);
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "La commande a été supprimée avec succès."
            );
        }

        return $this->redirectToRoute(route: 'app_admin_booking_index');
    }
}
