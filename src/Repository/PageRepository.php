<?php

namespace SymfonySimpleSite\Page\Repository;

use Doctrine\ORM\QueryBuilder;
use SymfonySimpleSite\Page\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PageRepository extends ServiceEntityRepository
{
    private string $alias;

    public function __construct(ManagerRegistry $registry)
    {
        $this->setAlias('p');
        parent::__construct($registry, Page::class);
    }

    public function getItemsQueryBuilderByParentId(int $pageId): QueryBuilder
    {
        $queryBuilder = $this
            ->getItemsQueryBuilder()
            ->andWhere("{$this->getAlias()}.parent=:pageId")
            ->setParameter("pageId", $pageId)
            ->orderBy("{$this->getAlias()}.createdDate", "DESC");
        return $queryBuilder;
    }

    public function getMainPage(int $recentlyLength): array
    {
        $page = $this->getItemsQueryBuilderByUrl("/")->getQuery()->getOneOrNullResult();
        if (empty($page)) {
            return [];
        }

        $queryBuilder = $this
            ->getItemsQueryBuilder()
            ->andWhere("{$this->getAlias()}.isRecentlyPreview=:true")
            ->setParameter("true", true)
            ->setMaxResults($recentlyLength)
            ->orderBy("{$this->getAlias()}.createdDate", "DESC");

        return [
            'page' => $page,
            'pageItems' => $this->getItemsQueryBuilderByParentId($page->getId()),
            'recentlyItems' => $queryBuilder->getQuery()->getResult()
        ];
    }

    public function getItemsQueryBuilderByUrl(string $url): QueryBuilder
    {
        $queryBuilder = $this
            ->getItemsQueryBuilder()
            ->andWhere("{$this->getAlias()}.url=:url")
            ->setParameter("url", $url)
            ->orderBy("{$this->getAlias()}.createdDate", "DESC");
        return $queryBuilder;
    }

    public function getItemsQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this
            ->createQueryBuilder($this->getAlias());
        return $queryBuilder;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }
}
