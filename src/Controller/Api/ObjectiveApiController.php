<?php

namespace App\Controller\Api;

use App\Entity\Objective;
use App\Entity\PrayerName;
use App\Entity\Program;
use App\Exception\AppException;
use App\Manager\ObjectiveManager;
use App\Manager\PrayerManager;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[Route('/api/objective', options: ['expose' => true], methods: ['POST'])]
class ObjectiveApiController extends AbstractController
{
    use LoggerTrait;

    /**
     * @throws AppException
     */
    #[Route('/new/{id}', name: 'api_objective_new')]
    public function new(Request $request, Program $program, ObjectiveManager $objectiveManager): JsonResponse
    {
        $prayerName = $objectiveManager->entityManager->find(PrayerName::class, $request->get('prayerName'));
        $objective = $objectiveManager->new($program, $prayerName, (int) $request->get('number'));
        return $this->json($objective, Response::HTTP_OK, [], [
            ObjectNormalizer::ATTRIBUTES => ['id', 'number'],
        ]);
    }

    #[Route('/stats/{id}', name: 'api_objective_stats')]
    public function stats(Objective $objective, PrayerManager $prayerManager): JsonResponse
    {
        return $this->json($prayerManager->stats($objective, 5));
    }
}
