<?php

namespace App\Controller\Api;

use App\Entity\Objective;
use App\Manager\PrayerManager;
use App\Repository\PrayerRepository;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/prayer", methods={"POST"}, options={"expose"=true})
 */
class PrayerApiController extends AbstractController
{
    use LoggerTrait;

    /**
     * @Route("/add/{id}", name="api_prayer_add")
     */
    public function add(Objective $objective, PrayerManager $prayerManager)
    {
        try {
            $response = $this->json($prayerManager->add($objective, $this->getUser()));
        } catch (\Exception $exception) {
            $response = $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @Route("/stats/{id}", name="api_prayer_stats")
     */
    public function stats(Objective $objective, PrayerManager $prayerManager)
    {
        try {
            $response = $this->json($prayerManager->stats($objective, 10));
        } catch (\Exception $exception) {
            $this->logger->error(__FUNCTION__, compact('exception'));
            $response = $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
