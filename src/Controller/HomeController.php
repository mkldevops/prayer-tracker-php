<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProgramRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(path: '', name: 'app_home')]
    public function index(ProgramRepository $repository, UserRepository $userRepository): Response
    {
        $programs = [];
        if (null !== $this->getUser()) {
            $programs = $repository->findBy(['enable' => true, 'user' => $this->getUser()], ['createdAt' => 'desc'], 5);
        }

        $users = $userRepository->count(['enable' => true]);

        return $this->render('home/index.html.twig', ['programs' => $programs, 'users' => $users]);
    }
}
