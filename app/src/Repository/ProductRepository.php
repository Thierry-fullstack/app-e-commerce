<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return array
     */
    public function getProductsGroupedByCategory(): array
    {
        return $this->createQueryBuilder('p')
            ->select('c.id AS categoryId, COUNT(p.id) AS productCount')
            ->join('p.categorie', 'c')
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $page
     * @param Category|null $category
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getProductsByCategory(int $page,?Category $category = null): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $data = $this->createQueryBuilder('p')
            ->where('p.isPublished LIKE :state')
            ->setParameter('state','1')
            ->addOrderBy('p.categorie','DESC');

        if(isset($category)){
            $data = $data
                ->join('p.categorie','c')
                ->andWhere(':categorie IN (c)')
                ->setParameter('categorie',$category);
        }

        $data->getQuery()->getResult();
        return $this->paginator->paginate($data,$page, 8);
    }

    public function findBySearch(SearchData $searchData):PaginatorInterface
    {
        $data = $this->createQueryBuilder('p')
            ->where('p.isPublished LIKE :state')
            ->setParameter('state','1')
            ->addOrderBy('p.name','DESC');

        if(!empty($searchData->categories)){
            $data = $data
                ->join('p.categorie','c')
                ->andWhere('c.id IN (:categorie')
                ->setParameter('categorie',$searchData->categories);
        }
        $data = $data->getQuery()->getResult();
        return $this->paginator->paginate($data,$searchData->page,8);
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
