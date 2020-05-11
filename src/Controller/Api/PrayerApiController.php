<?php

namespace App\Controller\Api;

use App\Entity\Objective;
use App\Manager\PrayerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/prayer", methods={"POST"}, options={"expose"=true})
 */
class PrayerApiController extends AbstractController
{
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
}
