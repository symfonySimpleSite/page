<?php

namespace SymfonySimpleSite\Page\Entity\Traits;

trait RecentlyPreviewTrait
{
    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isRecentlyPreview = false;

    public function isRecentlyPreview(): ?bool
    {
        return $this->isRecentlyPreview;
    }

    public function setIsRecentlyPreview(?bool $isRecentlyPreview): self
    {
        $this->isRecentlyPreview = $isRecentlyPreview;
        return $this;
    }
}