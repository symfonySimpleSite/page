<?php

namespace SymfonySimpleSite\Page\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use SymfonySimpleSite\Page\Entity\Interfaces\TemplateInterface;
use SymfonySimpleSite\Page\PageBundle;
use SymfonySimpleSite\Page\Service\GetTemplateService;

abstract class AbstractPageController extends AbstractController
{
    public const FLUSH_SUCCESS_KEY = 'success';
    public const FLUSH_ERROR_KEY = 'error';

    private string $template = '';
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private SluggerInterface $slugger;

    public function __construct(
        EntityManagerInterface $entityManager,
        GetTemplateService $getTemplateService,
        SluggerInterface $slugger,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->template = $getTemplateService->get($this->getPacketName());
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    protected function getPacketName(): string
    {
        return PageBundle::getConfigName();
    }

    protected function getTemplate(): string
    {
        return $this->template;
    }

    protected function getSlugger(): SluggerInterface
    {
        return $this->slugger;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function getEntityTemplate(): ?TemplateInterface
    {
        return null;
    }
}
