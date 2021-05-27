<?php

namespace SymfonySimpleSite\Page\Entity\Interfaces;

interface TemplateInterface
{
    public function getTemplate(): ?string;
    public function setTemplate(?string $template): self;
}
