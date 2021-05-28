<?php

namespace SymfonySimpleSite\Page\Entity\Traits;

trait ImageTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $image = "";

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }
}
