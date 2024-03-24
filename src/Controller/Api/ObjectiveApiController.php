<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Objective;
use App\Entity\PrayerName;
use App\Entity\Program;
use App\Exception\AppException;
use App\Manager\ObjectiveManager;
use App\Manager\PrayerManager;
use App\Repository\PrayerNameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/objective', options: ['expose' => true], methods: ['POST'])]
class ObjectiveApiController extends AbstractController
{
    /**
     * @throws AppException
     */
    #[Route('/new/{id}', name: 'api_objective_new')]
    public function new(Request $request, Program $program, ObjectiveManager $objectiveManager, PrayerNameRepository $prayerNameRepository): JsonResponse
    {
        $prayerName = $prayerNameRepository->find($request->get('prayerName'));
        if (!$prayerName instanceof PrayerName) {
            throw new AppException('Prayer not found');
        }

        $number = \is_scalar($request->get('number')) ? (int) $request->get('number') : 0;
        $objective = $objectiveManager->new($program, $prayerName, $number);

        return $this->json($objective, Response::HTTP_OK, [], [
            AbstractNormalizer::ATTRIBUTES => ['id', 'number'],
        ]);
    }

    /**
     * @throws AppException
     */
    #[Route('/stats/{id}', name: 'api_objective_stats')]
    public function stats(Objective $objective, PrayerManager $prayerManager): JsonResponse
    {
        return $this->json($prayerManager->stats($objective, 5));
    }
}
