<?php

namespace App\Controller;

use App\Entity\Objective;
use App\Form\Objective1Type;
use App\Repository\PrayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/objective")
 */
class ObjectiveController extends AbstractController
{
    /**
     * @Route("/{id}", name="objective_show", methods={"GET"})
     */
    public function show(Objective $objective, PrayerRepository $prayerRepository): Response
    {
        $lastPrayers = $prayerRepository->findBy(['objective' => $objective], ['createdAt' => 'desc'], 10);

        return $this->render('objective/show.html.twig', compact('lastPrayers', 'objective'));
    }

    /**
     * @Route("/{id}/edit", name="objective_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Objective $objective): Response
    {
        $form = $this->createForm(Objective1Type::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_show', ['id' => $objective->getProgram()->getId()]);
        }

        return $this->render('objective/edit.html.twig', [
            'objective' => $objective,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="objective_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Objective $objective): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objective->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($objective);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_show', ['id' => $objective->getProgram()->getId()]);
    }
}
