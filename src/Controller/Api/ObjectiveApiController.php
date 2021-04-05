<?php

namespace App\Controller\Api;

use App\Entity\Objective;
use App\Entity\PrayerName;
use App\Entity\Program;
use App\Manager\ObjectiveManager;
use App\Manager\PrayerManager;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/api/objective", methods={"POST"}, options={"expose"=true})
 */
class ObjectiveApiController extends AbstractController
{
    use LoggerTrait;

    /**
     * @Route("/new/{id}", name="api_objective_new")
     */
    public function new(Request $request, Program $program, ObjectiveManager $objectiveManager)
    {
        try {
            $prayerName = $objectiveManager->entityManager->find(PrayerName::class, $request->get('prayerName'));
            $objective = $objectiveManager->new($program, $prayerName, $request->get('number'));
            $response = $this->json($objective, Response::HTTP_OK, [], [
                ObjectNormalizer::ATTRIBUTES => ['id', 'number'],
            ]);
        } catch (\Exception $exception) {
            $this->logger->error(__METHOD__, compact('exception'));
            $response = $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @Route("/stats/{id}", name="api_objective_stats")
     */
    public function stats(Objective $objective, PrayerManager $prayerManager)
    {
        try {
            $response = $this->json($prayerManager->stats($objective, 5));
        } catch (\Exception $exception) {
            $this->logger->error(__FUNCTION__, compact('exception'));
            $response = $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
