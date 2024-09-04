<?php

declare(strict_types=1);

namespace App\Packagist\Infrastructure\Persistence\Repository;

use App\Packagist\Domain\Name;
use App\Packagist\Domain\Package;
use App\Packagist\Domain\PackageRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Repository\DatabaseRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Infrastructure\Persistence\Repository
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

    public function getAllNames(): iterable
    {
        $result = $this->createQueryBuilder('pkg')
            ->select('pkg.name.value', 'pkg.name.vendor')
            ->orderBy('pkg.name.value', 'ASC')
            ->orderBy('pkg.name.vendor', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($result as ['name.value' => $name, 'name.vendor' => $vendor]) {
            yield new Name($name, $vendor);
        }
    }

    public function getAll(): iterable
    {
        return $this->findAll();
    }
}
