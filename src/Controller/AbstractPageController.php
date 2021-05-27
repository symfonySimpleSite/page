<?php

namespace SymfonySimpleSite\Page\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SymfonySimpleSite\Page\Entity\Interfaces\TemplateInterface;
use SymfonySimpleSite\Page\PageBundle;
use SymfonySimpleSite\Page\Service\GetTemplateService;

abstract class AbstractPageController extends AbstractController
{
    public const FLUSH_SUCCESS_KEY = 'success';
    public const FLUSH_ERROR_KEY = 'error';

    protected string $template;

    public function __construct(GetTemplateService $getTemplateService)
    {
        $this->template = $getTemplateService->get(PageBundle::getConfigName());
    }

    protected function getEntityTemplate(): ?TemplateInterface
    {
        return null;
    }

    protected function getTemplate(string $packetName): string
    {
        $ret = $this->get('parameter_bag')->get($packetName);
        if (empty($ret['template']) || empty($ret['template']['default'])) {
            throw new \Exception('Can`t find parameter template.default');
        }

        $activeRoute = $this->get('request_stack')->getCurrentRequest()->get('_route');

        if (!empty($ret[$activeRoute])) {
            return $activeRoute;
        }

        if (!empty($this->getEntityTemplate())) {
            return $this->getEntityTemplate()->getTemplate();
        }

        return $ret['default'];
    }
}
