<?php

namespace SymfonySimpleSite\Page\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SymfonySimpleSite\Menu\Repository\MenuRepository;
use SymfonySimpleSite\Page\Entity\Page;
use SymfonySimpleSite\Page\PageBundle;
use SymfonySimpleSite\Page\Repository\PageRepository;
use SymfonySimpleSite\Page\Service\GetTemplateService;

class PageController extends AbstractPageController
{

    public function mainPage(PageRepository $pageRepository): Response
    {
        return $this->render('@Page/frontend/main_page.html.twig', [
            'template' => $this->getTemplate(),
            'params' => $this->get('parameter_bag')->get(PageBundle::getConfigName()),
            'items' => $pageRepository->getMainPage(30)
        ]);
    }

    public function detail(
        Request $request,
        PaginatorInterface $paginator,
        PageRepository $pageRepository,
        ?string $url = null
    ): Response {

        $urlsArray = explode('/', $url);
        $lastUrl = end($urlsArray);

        $page =
            $pageRepository->getItemsQueryBuilderByUrl($lastUrl)
                ->getQuery()
                ->getOneOrNullResult();


        if (!$page) {
            throw $this->createNotFoundException();
        }

        $params = [
            'page' => $page,
            'template' => $this->getTemplate(),
            'params' => $this->get('parameter_bag')->get(PageBundle::getConfigName())
        ];
        $template = 'detail';
        return $this->render('@Page/frontend/' . $template . '.html.twig', $params);
    }
}

