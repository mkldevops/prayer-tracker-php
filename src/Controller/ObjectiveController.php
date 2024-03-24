<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Objective;
use App\Form\ObjectiveType;
use App\Repository\PrayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/objective')]
class ObjectiveController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route(path: '/{id}', name: 'objective_show', methods: ['GET'])]
    public function show(Objective $objective, PrayerRepository $prayerRepository): Response
    {
        $lastPrayers = $prayerRepository->findBy(['objective' => $objective], ['createdAt' => 'desc'], 10);

        return $this->render('objective/show.html.twig', ['lastPrayers' => $lastPrayers, 'objective' => $objective]);
    }

    #[Route(path: '/{id}/edit', name: 'objective_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Objective $objective): Response
    {
        $form = $this->createForm(ObjectiveType::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('program_show', ['id' => $objective->getProgram()?->getId()]);
        }

        return $this->render('objective/edit.html.twig', [
            'objective' => $objective,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'objective_delete', methods: ['DELETE'])]
    public function delete(Request $request, Objective $objective): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objective->getId(), (string) $request->request->get('_token'))) {
            $this->entityManager->remove($objective);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('program_show', ['id' => $objective->getProgram()?->getId()]);
    }
}
