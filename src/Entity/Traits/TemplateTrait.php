<?php

namespace SymfonySimpleSite\Page\Entity\Traits;

trait TemplateTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $template;

    public function getTemplate(): ?string
    {
        return $this->template;
    }
    public function setTemplate(?string $template): self
    {
        $this->template = $template;
    }
}
