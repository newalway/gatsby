<?php

namespace ProjectBundle\Repository;

use Symfony\Component\Intl\Locale;

/**
 * StylingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StylingRepository extends \Doctrine\ORM\EntityRepository
{
	private $qb;

	public function findAllData($arr_query_data=false, $locale=false)
    {
		$locale = ($locale) ? $locale : Locale::getDefault();
  		$this->qb = $this->createQueryBuilder('s');

		//join translation
		$this->qb->join('s.translations', 'st')
				->select('s', 'st')
				->orderBy('s.position', 'ASC')
				->addOrderBy('s.createdAt', 'DESC');

		$this->qb->addSelect('s', 'se')
			->leftJoin('s.series', 'se')
			->leftJoin('se.translations', 'set');

		$this->qb->addSelect('c', 'ct')
			->leftJoin('s.productCategory', 'c')
			->leftJoin('c.translations', 'ct');
// dump($arr_query_data);
// exit;
  		if(isset($arr_query_data['q']) && $arr_query_data['q']){
			//search from translation
  			$this->qb->andWhere($this->qb->expr()->orX(
	  	      	$this->qb->expr()->like('st.title', ':query')
				// ,
				// $this->qb->expr()->like('set.title', ':query'),
				// $this->qb->expr()->like('ct.title', ':query')

	  			// $this->qb->expr()->like('evt.description', ':query')
			))
  			->setParameter('query', '%'.$arr_query_data['q'].'%');
  		}

		if (isset($arr_query_data['productCategory']) && $arr_query_data['productCategory']){
			$this->qb->andWhere($this->qb->expr()->orX(
				$this->qb->expr()->eq('c.id', ':productCategory')
			))
			->setParameter('productCategory', $arr_query_data['productCategory']);
		}
		if (isset($arr_query_data['series']) && $arr_query_data['series']){
			$this->qb->andWhere($this->qb->expr()->orX(
				$this->qb->expr()->eq('se.id', ':series')
			))
			->setParameter('series', $arr_query_data['series']);
		}

		// if(isset($arr_query_data['eventCategory']) && $arr_query_data['eventCategory']){
		// 	$this->qb->andWhere('ec.id = :event_category_id')
		// 		->setParameter('event_category_id', $arr_query_data['eventCategory']);
		// }

  		return $this->qb;
    }

	public function setPublic()
    {
        $this->qb->andWhere('NOW() >= s.publicDate')
                ->andWhere('s.status = 1');
    }

	public function findAllActiveData($arr_query_data=false, $locale=false)
    {
		$this->findAllData($arr_query_data, $locale);
		$this->setPublic();
		return $this->qb;
	}

	public function findActiveDataByCategory($arr_query_data=false, $locale=false, $sObj)
	{
		$this->findAllActiveData($arr_query_data, $locale);

		$this->qb->andWhere($this->qb->expr()->andX(
			$this->qb->expr()->eq('s.series', ':sObj')
		))
		->setParameter('sObj',$sObj)
		->andWhere('s.status = 1');

		// $this->qb->where($this->qb->expr()->andX(
		// 		$this->qb->expr()->eq('ec.id', ':catObj')
		// ))

		return $this->qb;
	}

	public function findActiveDataByEvent($arr_query_data=false, $locale=false, $eventObj)
	{
		$this->findAllActiveData($arr_query_data, $locale);

		$this->qb->andWhere($this->qb->expr()->andX(
			$this->qb->expr()->eq('e.id', ':eventObj')
		))
		->setParameter('eventObj',$eventObj)
		->andWhere('ec.status = 1');

		return $this->qb;
	}

	public function getActiveDataByRecent($arr_query_data=false, $locale=false, $eventObj)
	{
		$catObj = $eventObj->getEventCategory();
		$this->findActiveDataByCategory($arr_query_data, $locale,$catObj);


		$this->qb->andWhere($this->qb->expr()->andX(
			$this->qb->expr()->notLike('e.id', ':eventObj')

		))
		->setParameter('eventObj',$eventObj)
		 //->setParameter('catObj',$catObj)
		->andWhere('ec.status = 1');

		return $this->qb;
	}


}
