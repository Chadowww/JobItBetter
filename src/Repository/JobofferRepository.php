<?php

namespace App\Repository;

use App\Data\FilterData;
use App\Entity\Company;
use App\Entity\Joboffer;
use App\Entity\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @extends ServiceEntityRepository<Joboffer>
 *
 * @method Joboffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joboffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joboffer[]    findAll()
 * @method Joboffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobofferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joboffer::class);
    }

    public function save(Joboffer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Joboffer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(array $credentials): array
    {
        $credential = implode(' ', $credentials);
        $query = $this->createQueryBuilder('j');
        if ($credentials != null) {
            $query->where('MATCH_AGAINST(j.title, j.city, j.description) AGAINST(:words boolean) > 0')
                ->setParameter('words', $credential);
        }
        return $query->getQuery()->getResult();
    }
    public function findByCompany(): array
    {
        $query = $this->createQueryBuilder('j')
            ->select('c.name, COUNT(j.id) as total', 'c.logo', 'c.id')
            ->innerJoin('j.company', 'c')
            ->groupBy('c.id')
            ->orderBy('count(c)', 'DESC')
            ->setMaxResults(6)
            ->getQuery();
        return $query->getResult();
    }

    public function findByMySearch(Search $search): array
    {
        $query = $this->createQueryBuilder('jo')
            ->where('jo.company IN (
        SELECT c.id FROM App\Entity\Company c WHERE :companyId IS NULL OR c.id = :companyId
    )')
            ->andWhere('jo.job IN (
        SELECT j.id FROM App\Entity\Job j WHERE :jobId IS NULL OR j.id = :jobId
    )')
            ->andWhere('jo.contract IN (
        SELECT ct.id FROM App\Entity\Contract ct WHERE :contractId IS NULL OR ct.id = :contractId
    )')
            ->andWhere('jo.salary IN (
        SELECT s.id FROM App\Entity\Salary s WHERE :salaryId IS NULL OR s.id = :salaryId
    )')
            ->andWhere(':city IS NULL OR jo.city = :city')
            ->setParameter('companyId', $search->getCompany())
            ->setParameter('jobId', $search->getJob()?->getId())
            ->setParameter('contractId', $search->getContract()?->getId())
            ->setParameter('salaryId', $search->getSalary()?->getId())
            ->setParameter('city', $search->getCity())
            ->orderBy('jo.createdAt', 'DESC')
            ->getQuery();
        return $query->getResult();
    }

    public function findByFilter(FilterData $data): array
    {
        $query = $this->getFilterQuery($data, false);
        $query->orderBy('jo.createdAt', 'DESC');
        $query = $query->getQuery();

        return $query->getResult();
    }

    /**
     * @return integer[] Returns an array of salary
     */
    public function findMinMaxSalary(FilterData $data): array
    {
        $query = $this->createQueryBuilder('jo')
            ->select('MIN(jo.salaryMin) as min', 'MAX(jo.salaryMax) as max')
            ->getQuery()
            ->getScalarResult();
        return [(int)$query[0]['min'], (int)$query[0]['max']];
    }

    private function getFilterQuery(FilterData $data, bool $ignoreSalary = false): QueryBuilder
    {
        $query = $this->createQueryBuilder('jo')
            ->select('jo')
            ->join('jo.contract', 'c');
        if ($data->q !== null) {
            $query->where('MATCH_AGAINST(jo.title, jo.city ,jo.description) AGAINST(:q boolean) > 0')
                ->setParameter('q', '%' . $data->q . '%');
        }
        if ($data->minSalary !== null && $ignoreSalary === false) {
            $query->andWhere('jo.salaryMin >= :minSalary')
                ->setParameter('minSalary', $data->minSalary);
        }
        if ($data->maxSalary !== null && $ignoreSalary === false) {
            $query->andWhere('jo.salaryMax <= :maxSalary')
                ->setParameter('maxSalary', $data->maxSalary);
        }
        if ($data->contract !== null) {
            $contracts = [];
            foreach ($data->contract as $contract) {
                $contracts[] = $contract->getId();
            }
            if ($contracts !== []) {
                $query->andWhere('jo.contract IN (:contract)')
                    ->setParameter('contract', $contracts);
            }
        }
        if ($data->company !== null) {
            $query->andWhere('jo.company = :company')
                ->setParameter('company', $data->company);
        }
        if ($data->city !== null) {
            $cityNames = [];
            foreach ($data->city as $city) {
                $cityNames[] = $city->getCity();
            }
            if ($cityNames !== []) {
                $query->andWhere('jo.city IN (:city)')
                    ->setParameter('city', $cityNames);
            }
        }
        return $query   ;
    }
}
