<?php

namespace SymfonySimpleSite\Page\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use SymfonySimpleSite\Page\Entity\Page;
use SymfonySimpleSite\Page\Form\PageType;
use SymfonySimpleSite\Page\PageBundle;
use SymfonySimpleSite\Page\Repository\PageRepository;
use SymfonySimpleSite\Page\Service\GetTemplateService;

class AdminController extends AbstractPageController
{

    public function index(Request $request, PaginatorInterface $paginator, PageRepository $pageRepository): Response
    {
        $pagination = $paginator->paginate(
            $pageRepository->getItemsQueryBuilder()->getQuery(),
            $request->query->getInt('page', 1)
        );

        return $this->render('@Page/admin/index.html.twig', [
            'template' => $this->template,
            'pagination' => $pagination,
            'alias' => $pageRepository->getAlias()
        ]);
    }

    public function save(Request $request, SluggerInterface $slugger, ?Page $page = null): Response
    {
        if (empty($page)) {
            $page = new Page();
            $page->setCreatedDate(new \DateTime('now'));
            $page->setStatus(Page::STATUS_ACTIVE);
        }

        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (empty($page->getUrl())) {
                $page->setUrl($page->getName());
            }

            $page->setUrl($slugger->slug($page->getUrl()));
            $entityManager = $this->getDoctrine()->getManager();

            if (empty($page->getParent())) {
                $page->setUrl('page');
                $page->setType(Page::TYPE_SECTION);
            }

            if (empty($page->getId())) {
                $entityManager->persist($page);
            }
            try {
                $entityManager->flush();
                $this->addFlash(self::FLUSH_SUCCESS_KEY, 'Data saved!');
            } catch (\Exception $exception) {
                $this->addFlash(self::FLUSH_ERROR_KEY, 'Sorry, I can`t save data');
            }
            return $this->redirectToRoute('admin_page_index');
        }

        return $this->render('@Page/admin/save.html.twig', [
            'template' => $this->template,
            'form' => $form->createView(),
            'page' => $page
        ]);
    }

    public function delete(Page $page): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        try {
            $entityManager->remove($page);
            $entityManager->flush();
            $this->addFlash(self::FLUSH_SUCCESS_KEY, 'Data was deleted!');
        } catch (\Exception $exception) {
            $this->addFlash(self::FLUSH_ERROR_KEY, 'Sorry, I can`t delete data');
        }

        return $this->redirectToRoute('admin_page_index');
    }
}