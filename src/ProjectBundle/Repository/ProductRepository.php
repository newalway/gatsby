<?php

namespace ProjectBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Intl\Locale;

class ProductRepository extends EntityRepository
{
    private $qb;

    public function findAllData()
    {
      return $this->createQueryBuilder('p')
                ->orderBy('p.position', 'ASC')
                ->addOrderBy('p.createdAt', 'DESC');
    }

    public function findDataById($id)
    {
        $this->find($id);
        return $this;
    }

    public function selectProductSkuData($locale=false)
    {
        // inner join sku
        // $this->qb->select('p', 'sku', 'sku.price as sku_price', 'sku.sku as sku_sku')
        // ->join('p.skus', 'sku')

        $locale = ($locale) ? $locale : Locale::getDefault();
  		$this->qb = $this->createQueryBuilder('p');
        $this->qb->select('p', 'pt')

            ->addSelect("COUNT(distinct sku.id) as v_count")

            ->addSelect("(SELECT SUM( ss1.inventoryQuantity) as total
                            FROM ProjectBundle\Entity\Sku ss1
                            WHERE ss1.product = p.id
                                AND ss1.inventoryPolicyStatus = 1
                                AND ss1.status = 1)
                         AS v_inventory_quantity ")

            ->addSelect("SUM(distinct sku.defaultOption) as v_is_default_option")

            ->addSelect("(SELECT ss2.price
                            FROM ProjectBundle\Entity\Sku ss2
                            WHERE ss2.product = p.id
                                AND ss2.defaultOption = 1
                                AND ss2.status = 1)
                         AS v_default_price ")

            ->addSelect("(SELECT ss3.compareAtPrice
                            FROM ProjectBundle\Entity\Sku ss3
                            WHERE ss3.product = p.id
                                AND ss3.defaultOption = 1
                                AND ss3.status = 1)
                         AS v_default_compare_at_price ")

            ->addSelect("(SELECT ss4.sku
                            FROM ProjectBundle\Entity\Sku ss4
                            WHERE ss4.product = p.id
                                AND ss4.defaultOption = 1
                                AND ss4.status = 1)
                         AS v_default_sku ")

            ->addSelect("sku.price as v_price")
            ->addSelect("sku.compareAtPrice as v_compare_at_price")
            ->addSelect("sku.sku as v_sku")


            // Doctrine\ORM\Query\Expr::WITH
            ->leftjoin('p.skus', 'sku', "WITH", 'sku.status = 1')
            ->leftjoin('p.translations', 'pt')

            ->andWhere("pt.locale = '$locale'")
            ->groupBy('p.id')

            // ->orderBy('p.position', 'ASC')
            // ->addOrderBy('p.createdAt', 'DESC')
            // ->setParameter('locale', $locale)
            ;
    }

    public function setOrderBy()
    {
        $this->qb->addOrderBy('p.position', 'ASC')
            ->addOrderBy('p.createdAt', 'DESC');
    }

    public function findProductsDiscountsByDiscountIdDataJoined($discount_id, $locale=false)
    {
        $this->selectProductSkuData($locale);
        $this->setOrderBy();

        $this->qb->innerjoin('p.discounts', 'd')
            ->andWhere('d.id = :discount_id')
            ->setParameter('discount_id', $discount_id)
            ->orderBy('p.position', 'ASC')
            ->addOrderBy('p.createdAt', 'DESC');

        return $this->qb;
    }

    public function findProductsPromotionsByPromotionIdDataJoined($promotion_id, $locale=false)
    {
        $this->selectProductSkuData($locale);
        $this->setOrderBy();

        $this->qb->innerjoin('p.promotions', 'd')
            ->andWhere('d.id = :promotion_id')
            ->setParameter('promotion_id', $promotion_id)
            ->orderBy('p.position', 'ASC')
            ->addOrderBy('p.createdAt', 'DESC');

        return $this->qb;
    }

    public function findAllDataJoined($arr_query_data=false, $locale=false)
    {
        $this->selectProductSkuData($locale);

        //order data
        $this->qb->leftjoin('p.series', 'se')
            ->leftjoin('se.productCategory', 'c')
            ->addOrderBy('c.position', 'ASC')
            ->addOrderBy('se.position', 'ASC');
        // $this->qb->leftjoin('p.productCategory', 'pc')
        // ->leftjoin('pc.translations', 'pct')
        // ->leftjoin('pc.series', 'se')
        // ->leftjoin('se.translations', 'set');

        //default order
        $this->setOrderBy();

  		if(isset($arr_query_data['q'])){
            $q = $arr_query_data['q'];
            if($q){
                $this->qb->where($this->qb->expr()->orX(
                    $this->qb->expr()->like('pt.title', ':query'),
                    $this->qb->expr()->like('pt.description',':query'),
                    $this->qb->expr()->like('sku.sku',':query'),
                    $this->qb->expr()->like('p.sku',':query')
                ))
      			->setParameter('query', '%'.$q.'%');
            }
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

  		return $this->qb;
    }

    public function findAllActiveData($arr_query_data=false, $locale=false)
    {
        $this->selectProductSkuData($locale);

        $this->qb->andWhere('NOW() >= p.publishDate')
            ->andWhere($this->qb->expr()->andX(
                $this->qb->expr()->eq('p.status', ':status')
            ))
            ->setParameter('status', 1);

        if($arr_query_data){
            if(isset($arr_query_data['ddlPriceSort'])){
                $this->qb->orderBy('p.price', $arr_query_data['ddlPriceSort']);
            }else{
                $this->qb->orderBy('p.position', 'ASC')->addOrderBy('p.createdAt', 'DESC');
            }

            if(isset($arr_query_data['searchBox'])){
                $arr_searchBox = ($arr_query_data['searchBox']);
                $this->qb->andWhere($this->qb->expr()->orX(
                    $this->qb->expr()->like('pt.title', ':query'),
                    $this->qb->expr()->like('pt.description',':query')
                ))
                ->setParameter('query', '%'.$arr_searchBox.'%');
            }

        }else{
            $this->setOrderBy();
        }

        return $this->qb;
    }

    public function findActiveDataBySeries($series)
    {
        // $this->findAllActiveData();
        // $this->qb->join('p.series', 'se');
        // $this->qb->andWhere('se = :series')
        //             ->setParameter('series', $series);

		$this->qb = $this->createQueryBuilder('p');
		//join translation
		$this->qb->select('s','p')
            ->join('p.series', 's')
			->join('s.translations', 'st')
			->leftjoin('s.productCategory', 'pc')
			->leftjoin('pc.translations', 'pct')
			->where("s.id = '$series'")
            ->andWhere("p.status = 1 ")
			->orderBy('pc.position', 'ASC')
			->addOrderBy('s.position', 'ASC')
			->addOrderBy('s.createdAt', 'DESC');

		// $this->setSearchData($arr_query_data);

        return $this->qb;
    }

    /*
    public function getActiveDataById($id, $locale=false)
    {
        $this->findAllActiveData(array(), $locale);
        $this->qb->addSelect('ba', 'po', 'eq');
        $this->qb->leftjoin('p.brand', 'ba')
            ->leftjoin('p.power', 'po')
            ->leftjoin('p.equipment', 'eq');

        $this->qb->andWhere($this->qb->expr()->andX(
            $this->qb->expr()->eq('p.id', ':id')
        ))
        ->setParameter('id', $id);
        return $this->qb;
    }
    */

    public function getActiveDataById($id, $locale=false)
    {
        $this->findAllActiveData(array(), $locale);
        $this->qb->andWhere($this->qb->expr()->andX(
            $this->qb->expr()->eq('p.id', ':id')
        ))
        ->setParameter('id', $id);
        return $this->qb;
    }

    public function getActiveDataByProductsRelated($id, $product=false, $locale=false)
    {
        $locale = ($locale) ? $locale : Locale::getDefault();
        $this->selectProductSkuData($locale);
        $this->qb
            ->andWhere('NOW() >= p.publishDate')
            ->andWhere($this->qb->expr()->andX(
                $this->qb->expr()->eq('p.status', ':status'),
                $this->qb->expr()->eq('pt.locale', ':locale'),
                $this->qb->expr()->notLike('p.id', ':id')
            ))
            ->setParameter('status', 1)
            ->setParameter('locale', $locale)
            ->setParameter('id', $id);

        $series = $product->getSeries();
        if ($series){
            $this->qb->join('p.series', 'se');
            foreach ($series as $serie) {
                $this->qb->andWhere($this->qb->expr()->andX(
                    $this->qb->expr()->eq('se.id', ':series')
                ))->setParameter('series', $serie);
            }
        }

        /*
        if($product->getEquipment()){
            $equipment_id = $product->getEquipment()->getId();
            $this->qb->leftjoin('p.equipment', 'eq');
            $this->qb->andWhere($this->qb->expr()->andX(
                $this->qb->expr()->eq('eq.id',':equipment_id')
            ))->setParameter('equipment_id', $equipment_id);
        }
        */

        return $this->qb;
    }

    public function getActiveDataProductsByPromotionId($id, $locale=false)
    {
        $this->selectProductSkuData($locale);
        $this->setOrderBy();

        $this->qb->addSelect('pm')
            ->leftjoin('p.promotions', 'pm')
            ->andWhere('NOW() >= p.publishDate')
            ->andWhere($this->qb->expr()->andX(
                $this->qb->expr()->eq('pm.id', ':id'),
                $this->qb->expr()->eq('pm.status', ':status')
            ))
            ->setParameter('id', $id)
            ->setParameter('status', 1);
        return $this->qb;
    }

    public function getPublishProductsDiscountByDiscountCode($discount_code)
	{
		//QueryBuilder Expr
		$this->qb = $this->createQueryBuilder('p');
		$this->qb->select('p')
			->where('pd.discountCode = :discount_code')
			->setParameter('discount_code', $discount_code)
			->innerJoin('p.discounts', 'pd')
			->andWhere('NOW() >= p.publishDate')
			->andWhere('p.status = 1');
		return $this->qb;
	}

    /*
    public function getProductsDiscountsByDiscountId($discount_id, $locale=false)
    {
        $locale = ($locale) ? $locale : Locale::getDefault();
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, pt.title')
            ->innerjoin('p.discounts', 'd')
            ->leftjoin('p.translations', 'pt')
            ->where('d.id = :discount_id')
            ->andWhere("pt.locale = '$locale'")
            ->setParameter('discount_id', $discount_id)
            ;
        return $qb;
    }
    */

    /*
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
                ->createQuery('SELECT p FROM ProjectBundle:Product p ORDER BY p.name ASC')
                ->getResult();
    }

    public function findOneByIdJoinedToEquipment($productId)
    {
        $query = $this->getEntityManager()
                    ->createQuery(
                        'SELECT p, c FROM ProjectBundle:Product p
                        JOIN p.equipment c
                        WHERE p.id = :id'
                    )->setParameter('id', $productId);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAllActiveWithBrandId($brandId)
    {
        $query = $this->getEntityManager()
                    ->createQuery(
                        'SELECT p FROM ProjectBundle:Product p
                        WHERE p.status = :status
                        AND p.brand = :brandId
                        ORDER BY p.position asc, p.createdAt desc'
                    )->setParameter('status', 1)
                    ->setParameter('brandId', $brandId);
        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAllActiveWithSalons()
    {
        $query = $this->getEntityManager()->createQuery(
                    'SELECT p FROM ProjectBundle:Product p
                    JOIN p.salons s
                    WHERE p.status = :pstatus
                    AND s.status = :lstatus
                    ORDER BY p.position asc, p.createdAt desc'
                )->setParameter('pstatus', 1)
                ->setParameter('lstatus', 1);
        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    */


}
