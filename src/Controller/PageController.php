<?php

namespace SymfonySimpleSite\Page\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('@Page/page/index.html.twig');
    }
}
