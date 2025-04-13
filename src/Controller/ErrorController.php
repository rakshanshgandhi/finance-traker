<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorController extends AbstractController
{
    public function show(\Throwable $exception): Response
    {
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;

        return $this->render('errors/error.html.twig', [
            'code' => $statusCode,
            'message' => $this->getErrorMessage($statusCode),
        ]);
    }

    private function getErrorMessage(int $code): string
    {
        return match ($code) {
            404 => 'Page Not Found',
            500 => 'Internal Server Error',
            default => 'An unexpected error occurred',
        };
    }
}
