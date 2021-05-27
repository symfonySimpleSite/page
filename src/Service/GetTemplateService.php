<?php

namespace SymfonySimpleSite\Page\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use SymfonySimpleSite\Page\Entity\Interfaces\TemplateInterface;

class GetTemplateService
{
    private RequestStack $requestStack;
    private ParameterBagInterface $parameterBag;

    public function __construct(RequestStack $requestStack, ParameterBagInterface $parameterBag)
    {
        $this->requestStack = $requestStack;
        $this->parameterBag = $parameterBag;
    }

    public function get(string $packetName, ?TemplateInterface $entity = null): string
    {
        $ret = $this->parameterBag->get($packetName);

        if (empty($ret['template']) || empty($ret['template']['default'])) {
            throw new \Exception('Can`t find parameter template.default');
        }

        $activeRoute = $this->requestStack->getCurrentRequest()->get('_route');

        if (!empty($ret[$activeRoute])) {
            return $activeRoute;
        }

        if (!empty($entity)) {
            return $entity->getTemplate();
        }

        return $ret['template']['default'];

        dump($request, $ret); die;
    }
}