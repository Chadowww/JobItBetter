<?php

namespace App\Repository;

use App\Entity\Resume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Resume>
 *
 * @method Resume|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resume|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resume[]    findAll()
 * @method Resume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Resume::class);
        $this->paginator = $paginator;
    }

    public function save(Resume $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Resume $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchResumes(array $search): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $result = [];
        foreach ($search as $key => $value) {
            if ($value === true) {
                $query = $this->createQueryBuilder('r')
                    ->join('r.technology', 't')
                    ->join('t.category', 'c')
                    ->andWhere('t.name = :name')
                    ->setParameter('name', $key)
                    ->getQuery();
                foreach ($query->getResult() as $resume) {
                    if (!in_array($resume, $result)) {
                        $result[] = $resume;
                    }
                }
            }
        }

        return $this->paginator->paginate(
            $result,
            1,
            10
        );
    }

    public function lastResumes(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}
