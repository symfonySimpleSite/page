<?php

namespace SymfonySimpleSite\Page\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SymfonySimpleSite\Page\Entity\Page;
use SymfonySimpleSite\Page\PageBundle;
use SymfonySimpleSite\Page\Repository\PageRepository;
use SymfonySimpleSite\Page\Service\GetTemplateService;

class PageController extends AbstractPageController
{
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('@Page/frontend/index.html.twig', [
            'template' => $this->getTemplate(),
            'params' => $this->get('parameter_bag')->get(PageBundle::getConfigName()),
        ] + $pageRepository->getMainPage(5));
    }

    public function detail(
        Request $request,
        PaginatorInterface $paginator,
        PageRepository $pageRepository,
        ?string $url = null
    ): Response
    {

        if (empty($url)) {
            $url = 'page';
        }

        $page =
            $pageRepository->getItemsQueryBuilderByUrl($url)
                ->getQuery()
                ->getOneOrNullResult();

        $params = [
            'page' => $page,
            'template' => $this->getTemplate(),
            'params' => $this->get('parameter_bag')->get(PageBundle::getConfigName())
        ];
        $template = 'detail';

        if ($page->getType() == Page::TYPE_SECTION) {
            $params['pagination'] =
                $paginator->paginate(
                    $pageRepository->getItemsQueryBuilderByParentId($page->getId())->getQuery(),
                    $request->query->getInt('page', 1)
                );

            $template = 'section';
        }

        return $this->render('@Page/frontend/' . $template . '.html.twig', $params);
    }

}
