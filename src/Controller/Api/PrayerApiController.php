<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Objective;
use App\Entity\Prayer;
use App\Manager\PrayerManager;
use Exception;
use Fardus\Traits\Symfony\Controller\ResponseTrait;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/prayer', options: ['expose' => true])]
class PrayerApiController extends AbstractController
{
    use LoggerTrait;
    use ResponseTrait;

    #[Route(path: '/add/{id}', name: 'api_prayer_add', methods: ['POST'])]
    public function add(Objective $objective, PrayerManager $prayerManager)
    {
        try {
            $response = $this->json($prayerManager->add($objective, $this->getUser()));
        } catch (Exception $exception) {
            $response = $this->jsonError($exception);
        }

        return $response;
    }

    #[Route(path: '/delete/{id}', name: 'api_prayer_delete', methods: ['DELETE'])]
    public function delete(Prayer $prayer, PrayerManager $prayerManager)
    {
        try {
            $response = $this->json($prayerManager->delete($prayer, $this->getUser()));
        } catch (Exception $exception) {
            $response = $this->jsonError($exception);
        }

        return $response;
    }
}
