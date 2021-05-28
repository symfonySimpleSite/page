<?php

namespace SymfonySimpleSite\Page\Entity\Interfaces;

interface ImageInterface
{
    public function getImage(): ?string;

    public function setImage(?string $image): self;
}
