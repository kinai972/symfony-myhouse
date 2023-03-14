<?php

namespace App\Controller\Admin;

use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: 'admin', name: 'app_backOffice_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index', methods: [Request::METHOD_GET])]
    public function index(AdminRepository $repository): Response
    {
        return $this->render(view: 'backOffice/user/index.html.twig', parameters: [
            'admins' => $repository->findBy(criteria: [], orderBy: ['registeredAt' => 'DESC']),
        ]);
    }
}
