<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Form\Admin\SliderType;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: 'admin/slider', name: 'app_admin_slider_')]
class SliderController extends AbstractController
{
    #[Route('/', name: 'index', methods: [Request::METHOD_GET])]
    public function index(SliderRepository $repository): Response
    {
        return $this->render(view: 'admin/slider/index.html.twig', parameters: [
            'images' => $repository->findBy(criteria: [], orderBy: ['place' => 'ASC']),
        ]);
    }

    #[Route('/ajouter', name: 'create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request, SliderRepository $repository, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: SliderType::class, data: $slider = new Slider(), options: [
            'validation_groups' => [Constraint::DEFAULT_GROUP, 'create']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nbImages = count($repository->findAll());

            $slider->setPlace($nbImages + 1);

            $manager->persist($slider);
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "L'image a été ajoutée au slider avec succès."
            );

            return $this->redirectToRoute(route: 'app_admin_slider_index');
        }

        return $this->render(view: 'admin/slider/create.html.twig', parameters: [
            'title' => "BACKOFFICE | Ajout d'une image au slider",
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(Slider $slider, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(type: SliderType::class, data: $slider)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "L'image a été modifiée avec succès."
            );

            return $this->redirectToRoute(route: 'app_admin_slider_index');
        }

        return $this->render(view: 'admin/slider/update.html.twig', parameters: [
            'title' => "BACKOFFICE | Modification d'une image au slider",
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/changer-ordre', name: 'order', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function orderSteps(
        Request $request,
        SliderRepository $repository,
        EntityManagerInterface $manager,
    ): Response {
        if ($data = json_decode($request->getContent(), true)) {
            if (empty($data['images'])) {
                return $this->json(data: ['message' => "Une erreur est survenue."], status: 400);
            }

            $orderedImages = $data['images'];

            foreach ($orderedImages as $orderedImage) {
                if (empty($orderedImage['id']) || empty($orderedImage['place'])) {
                    return $this->json(data: ['message' => "Une erreur est survenue."], status: 400);
                }
            }

            // Vérification du nombre d'images
            if (count($orderedImages) !== count($repository->findAll())) {
                return $this->json(['message' => "Le nombre d'étapes est incorrect."], 400);
            }

            // Vérification de la présence de toutes les images
            foreach ($orderedImages as $orderedImage) {
                if ($repository->find($orderedImage['id']) === null) {
                    return $this->json(['message' => 'Des images sont manquantes.'], 400);
                }
            }

            // Vérification de l'unicité des positions
            $positions = [];
            foreach ($orderedImages as $image) {
                $positions[] = $image['place'];
            }

            for ($i = 1; $i <= count($positions); $i++) {
                if (!in_array($i, $positions)) {
                    return $this->json(['message' => 'Les positions ne sont pas valides.'], 400);
                }
            }

            foreach ($orderedImages as $orderedImage) {
                $slider = $repository->find($orderedImage['id']);
                $slider->setPlace($orderedImage['place']);
            }
            $manager->flush();


            $this->addFlash('success', "L'ordre de vos images a été mis à jour avec succès.");

            return $this->json([
                'redirect_url' => $this->generateUrl('app_admin_slider_index')
            ]);
        }

        return $this->render(view: 'admin/slider/order.html.twig', parameters: [
            'images' => $repository->findBy(criteria: [], orderBy: ['place' => 'ASC']),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, Slider $slider, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $slider->getId(), $request->request->get('_token'))) {
            $manager->remove($slider);
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "L'image a été supprimée du slider avec succès."
            );
        }

        return $this->redirectToRoute(route: 'app_admin_slider_index');
    }
}
