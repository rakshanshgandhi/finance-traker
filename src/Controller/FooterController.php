<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FooterController extends AbstractController
{
    #[Route('/privacy-policy', name: 'app_privacy_policy')]
    public function privacy(): Response
    {
        return $this->render('privacy_policy.html.twig');
    }

    #[Route('/terms-service', name: 'app_terms_service')]
    public function terms(): Response
    {
        return $this->render('terms_of_service.html.twig');
    }
}
