<?php

namespace ProjectBundle\Repository;

/**
 * HolidayRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HolidayRepository extends \Doctrine\ORM\EntityRepository
{
    private $qb;

    public function findAllData($arr_query_data=false)
    {
        $this->qb = $this->createQueryBuilder('h')->OrderBy('h.holidayDate', 'ASC');
        $this->setSearchData($arr_query_data);

        return $this->qb;
    }

    public function findActiveData()
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $this->qb = $this->createQueryBuilder('h')->where('h.status = 1')
                         ->OrderBy('h.holidayDate', 'ASC');

        $this->qb->where($this->qb->expr()->andX(
            $this->qb->expr()->gte('h.holidayDate', ':today')
        ))
        ->setParameter('today', $today);

        return $this->qb;
    }

    public function findPublicHolidayByDate($delivery_date)
    {
        $this->qb = $this->createQueryBuilder('h')->where('h.status = 1');

        $arr_query_data['holidayDate'] = $delivery_date;
        $this->setSearchData($arr_query_data);

        return $this->qb;
    }

    protected function setSearchData($arr_query_data)
    {
        if(isset($arr_query_data['q'])){
            $this->qb->andWhere($this->qb->expr()->orX(
                $this->qb->expr()->like('h.title', ':query')
            ))
            ->setParameter('query', '%'.$arr_query_data['q'].'%');
        }

        if(isset($arr_query_data['holidayDate'])){
            $this->qb->andWhere($this->qb->expr()->andX(
                $this->qb->expr()->eq('h.holidayDate', ':holiday_date')
            ))
            ->setParameter('holiday_date', $arr_query_data['holidayDate']);
        }
    }

}
