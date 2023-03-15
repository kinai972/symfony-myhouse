<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Form\Admin\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: 'admin/chambres', name: 'app_admin_room_')]
class RoomController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: [Request::METHOD_GET])]
    public function index(RoomRepository $repository): Response
    {
        return $this->render(view: 'admin/room/index.html.twig', parameters: [
            'rooms' => $repository->findBy(criteria: [], orderBy: ['registeredAt' => 'DESC']),
        ]);
    }

    #[Route('/ajouter', name: 'create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: RoomType::class, data: $room = new Room(), options: [
            'validation_groups' => [Constraint::DEFAULT_GROUP, 'create']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($room);
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "La chambre {$room->getTitle()} a été ajoutée avec succès."
            );

            return $this->redirectToRoute(route: 'app_admin_room_index');
        }

        return $this->render(view: 'admin/room/create.html.twig', parameters: [
            'title' => "BACKOFFICE | Ajout d'une chambre",
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Room $room, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: RoomType::class, data: $room)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "La chambre {$room->getTitle()} a été modifiée avec succès."
            );

            return $this->redirectToRoute(route: 'app_admin_room_index');
        }

        return $this->render(view: 'admin/room/update.html.twig', parameters: [
            'title' => "BACKOFFICE | Modification d'une chambre",
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, Room $room, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $room->getId(), $request->request->get('_token'))) {
            $manager->remove($room);
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "La chambre a été supprimée avec succès."
            );
        }

        return $this->redirectToRoute(route: 'app_admin_room_index');
    }
}
