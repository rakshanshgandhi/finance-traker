<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): RedirectResponse
    {
        return new RedirectResponse('/temp');
    }
}
