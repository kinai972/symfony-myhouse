<?php

namespace App\Controller\Guest;

use App\Entity\Room;
use App\Entity\Booking;
use App\Form\Guest\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends AbstractController
{
    #[Route('/reserver-chambre/{id}', name: 'app_guest_booking_book')]
    public function book(Room $room, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: BookingType::class, data: $booking = new Booking(), options: [
            'validation_groups' => [Constraint::DEFAULT_GROUP, 'guest']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setRoom($room);

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
                message: "La chambre {$room->getTitle()} a été réservée avec succès."
            );

            return $this->redirectToRoute(route: 'app_guest_guest_home');
        }

        return $this->render(view: 'guest/booking/book.html.twig', parameters: [
            'title' => "Notre chambre « {$room->getTitle()} » à réserver",
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }
}
