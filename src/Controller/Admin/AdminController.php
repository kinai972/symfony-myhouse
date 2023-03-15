<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Form\Admin\AdminType;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;

#[Route(path: 'admin/administrateurs', name: 'app_admin_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index', methods: [Request::METHOD_GET])]
    public function index(AdminRepository $repository): Response
    {
        return $this->render(view: 'admin/admin/index.html.twig', parameters: [
            'admins' => $repository->findBy(criteria: [], orderBy: ['registeredAt' => 'DESC']),
        ]);
    }

    #[Route('/ajouter', name: 'create', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    #[IsGranted('ROLE_SUPER_ADMIN', message: "Vous n'êtes pas autorisé à accéder à cette page.")]
    public function create(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(type: AdminType::class, data: $admin = new Admin(), options: [
            'validation_groups' => [Constraint::DEFAULT_GROUP, 'password']
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setPassword(
                $hasher->hashPassword(
                    $admin,
                    $admin->getPlainPassword()
                )
            );

            $manager->persist($admin);
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "L'administrateur {$admin->getUsername()} a été ajouté avec succès."
            );

            return $this->redirectToRoute(route: 'app_admin_admin_index');
        }

        return $this->render(view: 'admin/admin/create.html.twig', parameters: [
            'title' => "BACKOFFICE | Ajout d'un administrateur",
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'update', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function update(
        Admin $admin,
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $manager
    ): Response {
        /** @var Admin */
        $currentAdmin = $this->getUser();

        if (!$this->isGranted('ROLE_SUPER_ADMIN') && $currentAdmin->getId() !== $admin->getId()) {
            throw $this->createAccessDeniedException(
                message: "Vous n'êtes pas autorisé à modifier les informations d'un autre administrateur que vous même."
            );
        }

        $form = $this->createForm(type: AdminType::class, data: $admin)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($admin->getPlainPassword() !== null) {
                $admin->setPassword(
                    $hasher->hashPassword(
                        $admin,
                        $admin->getPlainPassword()
                    )
                );
            }

            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "L'administrateur {$admin->getUsername()} a été modifié avec succès."
            );

            return $this->redirectToRoute(route: 'app_admin_admin_index');
        }

        return $this->render(view: 'admin/admin/update.html.twig', parameters: [
            'title' => "BACKOFFICE | Modification d'un administrateur",
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, Admin $admin, EntityManagerInterface $manager): Response
    {
        /** @var Admin */
        $currentAdmin = $this->getUser();

        if (!$this->isGranted('ROLE_SUPER_ADMIN') && $currentAdmin->getId() !== $admin->getId()) {
            throw $this->createAccessDeniedException(
                message: "Vous n'êtes pas autorisé à supprimer un autre administrateur que vous-même."
            );
        }

        if ($this->isCsrfTokenValid('delete' . $admin->getId(), $request->request->get('_token'))) {
            $manager->remove($admin);
            $manager->flush();

            $this->addFlash(
                type: 'success',
                message: "L'administrateur {$admin->getUsername()} a été supprimé avec succès."
            );
        }

        return $this->redirectToRoute(route: 'app_admin_admin_index');
    }
}
