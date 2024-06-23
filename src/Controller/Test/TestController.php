<?php
declare(strict_types=1);

namespace App\Controller\Test;

use App\Service\Test\TestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TestController extends AbstractController
{
    public function __construct(
        private TestService $testService
    ) {
    }

    public function test(): Response
    {
        return $this->render('test.html.twig', [
            'page_title' => 'Test Page',
        ]);
    }

    /*public function test(): JsonResponse
    {
        return new JsonResponse(
            $this->testService->test()
        );
    }*/

    public function testJson(): JsonResponse
    {
        return new JsonResponse(
            $this->testService->test()
        );
    }
}
