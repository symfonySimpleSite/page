<?php

namespace SymfonySimpleSite\Page\Entity\Interfaces;

interface RecentlyPreviewInterface
{
    public function isRecentlyPreview(): ?bool;
    public function setIsRecentlyPreview(?bool $image): self;
}