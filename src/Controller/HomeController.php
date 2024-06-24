<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class HomeController extends AbstractController
{
    public function __construct(
    ) {
    }

    public function home(): Response
    {
        return $this->render('test.html.twig', [
            'page_title' => 'Home Page',
        ]);
    }
}
