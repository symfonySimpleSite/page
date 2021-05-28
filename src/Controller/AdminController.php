<?php

namespace SymfonySimpleSite\Page\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SymfonySimpleSite\Page\Entity\Page;
use SymfonySimpleSite\Page\Form\PageType;
use SymfonySimpleSite\Page\PageBundle;
use SymfonySimpleSite\Page\Repository\PageRepository;

class AdminController extends AbstractAdminController
{

    public function index(Request $request, PaginatorInterface $paginator, PageRepository $pageRepository): Response
    {
        $pagination = $paginator->paginate(
            $pageRepository->getItemsQueryBuilder()->getQuery(),
            $request->query->getInt('page', 1)
        );

        return $this->render('@Page/admin/index.html.twig', [
            'template' => $this->getTemplate(),
            'pagination' => $pagination,
            'alias' => $pageRepository->getAlias()
        ]);
    }

    public function save(Request $request, ?Page $page = null): Response
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

            if ($page->getUrl() != "/") {
                $page->setUrl($this->getSlugger()->slug($page->getUrl()));
            }

            $entityManager = $this->getEntityManager();

            if (empty($page->getParent()) && $page->getUrl() != "/") {
                $page->setUrl('page');
                $page->setType(Page::TYPE_SECTION);
            }

            if (empty($page->getId())) {
                $entityManager->persist($page);
            }

            $entityManager->beginTransaction();
            try {
                $this->uploadImage($form, PageBundle::getConfigName(), $page);
                $entityManager->flush();
                $this->addFlash(self::FLUSH_SUCCESS_KEY, 'Data saved!');
                $entityManager->commit();
                return $this->redirectToRoute('admin_page_index');
            } catch (\Exception $exception) {
                $this->getLogger()->error(
                    'admin_save_page', [
                        'message' => $exception->getMessage(),
                        'trace' => $exception->getTraceAsString()
                    ]
                );
                $entityManager->rollback();
                $this->addFlash(self::FLUSH_ERROR_KEY, 'Sorry, I can`t save data');
            }
        }

        return $this->render('@Page/admin/save.html.twig', [
            'template' => $this->getTemplate(),
            'form' => $form->createView(),
            'page' => $page,
            'params' => $this->get('parameter_bag')->get(PageBundle::getConfigName())
        ]);
    }

    public function delete(Page $page): Response
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();
        try {
            $entityManager->remove($page);
            $entityManager->flush();
            $this->deletePageImage(PageBundle::getConfigName(), $page);
            $entityManager->commit();
            $this->addFlash(self::FLUSH_SUCCESS_KEY, 'Data was deleted!');
        } catch (\Exception $exception) {
            $entityManager->rollback();
            $this->getLogger()->error(
                'admin_save_page', [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]
            );
            $this->addFlash(self::FLUSH_ERROR_KEY, 'Sorry, I can`t delete data');
        }

        return $this->redirectToRoute('admin_page_index');
    }

    public function deletePageImage(Page $page): Response
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();
        try {
            $this->deleteImage(PageBundle::getConfigName(), $page);
            $entityManager->flush();
            $entityManager->commit();
            $this->addFlash(self::FLUSH_SUCCESS_KEY, 'Image was deleted!');
        }  catch (\Exception $exception) {
            $entityManager->rollback();
            $this->getLogger()->error(
                'admin_save_page', [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]
            );
            $this->addFlash(self::FLUSH_ERROR_KEY, 'Sorry, I can`t delete image');
        }

        return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
    }
}