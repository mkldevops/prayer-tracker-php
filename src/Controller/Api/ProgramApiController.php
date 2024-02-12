<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Program;
use App\Exception\AppException;
use App\Manager\ProgramManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/program', options: ['expose' => true])]
class ProgramApiController extends AbstractController
{
    #[Route(path: '/stats/{id}', name: 'api_program_stats', methods: ['GET'])]
    public function stats(Program $program, ProgramManager $programManager): JsonResponse
    {
        return $this->json($programManager->stats($program, 7));
    }

    /**
     * @throws AppException
     */
    #[Route(path: '/count-day/{id}', name: 'api_program_count_day', methods: ['GET'])]
    public function countDay(Program $program, ProgramManager $programManager): JsonResponse
    {
        return $this->json($programManager->countDay($program));
    }
}
