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
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        PageRepository $pageRepository,
        ?string $url = null
    ): Response {

        if (empty($url)) {
            $url = 'page';
        }

        $page =
            $pageRepository->getItemsQueryBuilderByUrl($url)
            ->getQuery()
            ->getOneOrNullResult();

        $params = [
            'page' => $page,
            'template' => $this->template
        ];
        $template = 'detail';

        if ($page->getType() == Page::TYPE_SECTION) {
            $params['pagination'] =
                $paginator->paginate(
                    $pageRepository->getItemsQueryBuilderByParentId($page->getId())->getQuery(),
                    $request->query->getInt('page', 1)
                );

            $template = 'index';
        }

        return $this->render('@Page/frontend/'.$template.'.html.twig', $params);
    }

}
