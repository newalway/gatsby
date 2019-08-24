<?php

namespace ProjectBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Symfony\Component\Intl\Locale;

class ShowroomRepository extends EntityRepository
{
    private $qb;

    public function findAllDataJoined($arr_query_data=false)
    {
        $this->qb = $this->createQueryBuilder('s');
        $this->qb->select('s', 'st', 'l','lt')
                    ->join('s.translations', 'st')
                    ->leftjoin('s.location', 'l')
                    ->leftjoin('l.translations', 'lt')
                    ->orderBy('l.position', 'ASC')
    				->addOrderBy('l.createdAt', 'DESC')
                    ->addOrderBy('s.position', 'ASC')
                    ->addOrderBy('s.createdAt', 'DESC');
        return $this->qb;
    }

    public function findAllData($arr_query_data=false)
    {
        $this->qb = $this->createQueryBuilder('s')
                ->select('s', 'st')
                ->join('s.translations', 'st')
                ->orderBy('s.position', 'ASC')
                ->addOrderBy('s.createdAt', 'DESC');

        $this->setSearchData($arr_query_data);

        return $this->qb;
    }

    protected function setSearchData($arr_query_data)
	{
		$q = $arr_query_data['q'];
        if($q){
            $this->qb->where($this->qb->expr()->orX(
                $this->qb->expr()->like('st.title', ':query'),
                $this->qb->expr()->like('st.address', ':query')
            ))
            ->setParameter('query', '%'.$q.'%');
        }
	}

    public function findAllActiveData($locale=false)
    {
        $locale = ($locale) ? $locale : Locale::getDefault();

        $this->qb = $this->createQueryBuilder('s');
        $this->qb->select('s','st')
            ->leftjoin('s.translations', 'st')
            ->orderBy('s.position', 'ASC')
            ->addOrderBy('s.createdAt', 'DESC')
            ->andWhere($this->qb->expr()->andX(
                $this->qb->expr()->like('s.status', ':status'),
				$this->qb->expr()->like('st.locale', ':locale')
            ))
            ->setParameters(array(
                'status' => 1,
				'locale' => $locale
            ));

      return $this->qb;
    }

    public function getShowroomByProduct($product, $locale=false)
	{
        $locale = ($locale) ? $locale : Locale::getDefault();

		$query = $this->getEntityManager()
			->createQuery(
				'SELECT sr, srt  FROM ProjectBundle:Showroom sr
				JOIN sr.products p
				LEFT JOIN sr.translations srt
				WHERE p.status = :status
					AND p.id = :product_id
					AND srt.locale = :locale'

			)->setParameters(array(
				'status'=>1,
				'product_id'=>$product->getId(),
				'locale'=>$locale
			));
			// ORDER BY a.position asc, a.createdAt desc'

		return $query->getResult();
	}
}
