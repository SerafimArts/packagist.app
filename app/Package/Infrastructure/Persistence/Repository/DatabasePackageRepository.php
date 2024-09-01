<?php

declare(strict_types=1);

namespace App\Package\Infrastructure\Persistence\Repository;

use App\Package\Domain\Name;
use App\Package\Domain\Package;
use App\Package\Domain\PackageRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Repository\DatabaseRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Infrastructure\Persistence\Repository
 *
 * @template-extends DatabaseRepository<Package>
 */
final class DatabasePackageRepository extends DatabaseRepository implements PackageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    public function findByName(Name $name): ?Package
    {
        return $this->createQueryBuilder('pkg')
            ->andWhere('pkg.name.value = :name')
            ->andWhere('pkg.name.vendor = :vendor')
            ->setParameter('name', $name->value)
            ->setParameter('vendor', $name->vendor)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getAll(): iterable
    {
        return $this->createQueryBuilder('pkg')
            ->orderBy('pkg.name.value', 'ASC')
            ->orderBy('pkg.name.vendor', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
