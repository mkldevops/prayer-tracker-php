<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="app_home")
     */
    public function index(ProgramRepository $repository, UserRepository $userRepository)
    {
        $programs = [];
        if ($this->getUser()) {
            $programs = $repository->findBy(['enable' => true, 'user' => $this->getUser()], ['createdAt' => 'desc'], 5);
        }

        $users = $userRepository->count(['enable' => true]);

        return $this->render('home/index.html.twig', compact('programs', 'users'));
    }
}
