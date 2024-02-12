<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Objective;
use App\Entity\Prayer;
use App\Exception\AppException;
use App\Manager\PrayerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/prayer', options: ['expose' => true])]
class PrayerApiController extends AbstractController
{
    #[Route(path: '/add/{id}', name: 'api_prayer_add', methods: ['POST'])]
    public function add(Objective $objective, PrayerManager $prayerManager): JsonResponse
    {
        return $this->json($prayerManager->add($objective, $this->getUser()));
    }

    /**
     * @throws AppException
     */
    #[Route(path: '/delete/{id}', name: 'api_prayer_delete', methods: ['DELETE'])]
    public function delete(Prayer $prayer, PrayerManager $prayerManager): JsonResponse
    {
        return $this->json($prayerManager->delete($prayer, $this->getUser()));
    }
}
