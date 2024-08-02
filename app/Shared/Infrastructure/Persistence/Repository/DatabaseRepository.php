<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use JetBrains\PhpStorm\Language;

/**
 * @template TEntity of object
 * @template-extends ServiceEntityRepository<TEntity>
 */
abstract class DatabaseRepository extends ServiceEntityRepository
{
    /**
     * @param non-empty-string $dql
     */
    protected function dql(#[Language('DQL')] string $dql): Query
    {
        $em = $this->getEntityManager();

        return $em->createQuery($dql);
    }

    /**
     * @param non-empty-string $sql
     */
    protected function sql(#[Language('SQL')] string $sql, ResultSetMapping $rsm): NativeQuery
    {
        $em = $this->getEntityManager();

        return $em->createNativeQuery($sql, $rsm);
    }
}
