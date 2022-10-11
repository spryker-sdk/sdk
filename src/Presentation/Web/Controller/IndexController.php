<?php

namespace SprykerSdk\Sdk\Presentation\Web\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return new Response('<h1>Hi! SDK is working on Web server.</h1>');
    }
}
