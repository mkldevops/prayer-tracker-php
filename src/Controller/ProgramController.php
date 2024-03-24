<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Objective;
use App\Entity\Program;
use App\Form\ObjectiveType;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/program')]
class ProgramController extends AbstractController
{
    public function __construct(
        readonly private EntityManagerInterface $entityManager
    ) {}

    #[Route(path: '/', name: 'program_index', methods: ['GET'])]
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render('program/index.html.twig', [
            'programs' => $programRepository->findBy(['user' => $this->getUser()]),
        ]);
    }

    #[Route(path: '/new', name: 'program_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($program);
            $this->entityManager->flush();

            $this->addFlash('success', sprintf('Great ! your program %s is added successfully', $program));

            return $this->redirectToRoute('program_show', ['id' => $program->getId()]);
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'program_show', methods: ['GET', 'POST'])]
    public function show(Request $request, TranslatorInterface $translator, Program $program = null): Response
    {
        if (!$program instanceof Program || $program->getUser() !== $this->getUser()) {
            $this->addFlash('info', $translator->trans("You don't have a program with id ".$request->get('id')));

            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(ObjectiveType::class, new Objective());

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'program_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('program_show', ['id' => $program->getId()]);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'program_delete', methods: ['DELETE'])]
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), (string) $request->request->get('_token'))) {
            $this->entityManager->remove($program);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('program_index');
    }
}
