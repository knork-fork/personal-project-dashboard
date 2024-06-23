<?php
declare(strict_types=1);

namespace App\Controller\Test;

use App\Service\Test\TestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TestController extends AbstractController
{
    public function __construct(
        private TestService $testService
    ) {
    }

    public function test(): JsonResponse
    {
        return new JsonResponse(
            $this->testService->test()
        );
    }
}
