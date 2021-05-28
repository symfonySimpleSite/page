<?php

namespace SymfonySimpleSite\Page\Entity;

use SymfonySimpleSite\Page\Entity\Interfaces\ImageInterface;
use SymfonySimpleSite\Page\Entity\Interfaces\RecentlyPreviewInterface;
use SymfonySimpleSite\Page\Entity\Interfaces\TemplateInterface;
use SymfonySimpleSite\Page\Entity\Traits\ImageTrait;
use SymfonySimpleSite\Page\Entity\Traits\RecentlyPreviewTrait;
use SymfonySimpleSite\Page\Entity\Traits\TemplateTrait;
use SymfonySimpleSite\Page\Repository\PageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PageRepository::class)
 * @ORM\Table(name="page",
 *  uniqueConstraints={
 *        @ORM\UniqueConstraint(name="url", columns={"url"})
 *     }
 * )
 */
class Page implements ImageInterface, TemplateInterface, RecentlyPreviewInterface
{
    use ImageTrait, TemplateTrait, RecentlyPreviewTrait;

    public const STATUS_ACTIVE = 1;
    public const TYPE_PAGE = 1;
    public const TYPE_SECTION = 2;
    public const TYPE_URL = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $url = "";

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $keywords;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $preview;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $body;

    /**
     * @ORM\Column(type="smallint")
     */
    private ?int $type;

    /**
     * @ORM\Column(type="smallint")
     */
    private ?int $status;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class)
     */
    private ?Page $parent = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ? \DateTime $createdDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?Page $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public static function getAllTypes(): array
    {
        return [
            'Page' =>self::TYPE_PAGE,
            'Section' => self::TYPE_SECTION,
            'Url' =>self::TYPE_URL,
        ];
    }
}
