<?php

namespace App\Controller\Api;

use Exception;
use App\Entity\Program;
use App\Manager\ProgramManager;
use Fardus\Traits\Symfony\Controller\ResponseTrait;
use Fardus\Traits\Symfony\Manager\LoggerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/program', options: ['expose' => true])]
class ProgramApiController extends AbstractController
{
    use LoggerTrait;
    use ResponseTrait;

    #[Route(path: '/stats/{id}', name: 'api_program_stats', methods: ['GET'])]
    public function stats(Program $program, ProgramManager $programManager)
    {
        try {
            $response = $this->json($programManager->stats($program, 7));
        } catch (Exception $exception) {
            $response = $this->jsonError($exception);
        }

        return $response;
    }

    #[Route(path: '/count-day/{id}', name: 'api_program_count_day', methods: ['GET'])]
    public function countDay(Program $program, ProgramManager $programManager)
    {
        try {
            $response = $this->json($programManager->countDay($program));
        } catch (Exception $exception) {
            $response = $this->jsonError($exception);
        }

        return $response;
    }
}
