<?php

namespace ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * ProductCategory
 *
 * @ORM\Table(name="product_category")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\ProductCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProductCategory
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $image;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":0})
     */
    private $position = 0;

    /** @ORM\Column(name="updated_at", type="datetime", nullable = true) */
    private $updatedAt;

  	/** @ORM\Column(name="created_at", type="datetime") */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="Series", mappedBy="productCategory")
     */
    private $series;

    /**
     * @ORM\OneToMany(targetEntity="Banner" , mappedBy="productCategory")
     */
    private $bannerIndex;

    /**
     * @ORM\OneToMany(targetEntity="Styling" , mappedBy="productCategory")
     */
    private $styling;

    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    public function __construct()
    {
        $this->series = new ArrayCollection();
    }

    /**
  	 *
  	 * @ORM\PrePersist
  	 * @ORM\PreUpdate
  	 */
  	public function updatedTimestamps()
  	{
      $this->setUpdatedAt(new \DateTime('now'));
      if ($this->getCreatedAt() == null) {
        $this->setCreatedAt(new \DateTime('now'));
      }
  	}

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     *
     * @param int id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of Image
     *
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of Image
     *
     * @param mixed image
     *
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function removeImage()
	{
		$this->setImage(null);
	}

    /**
     * Get the value of Position
     *
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set the value of Position
     *
     * @param mixed position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get the value of Updated At
     *
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of Updated At
     *
     * @param mixed updatedAt
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of Created At
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of Created At
     *
     * @param mixed createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * Get the value of Series
     *
     * @return mixed
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set the value of Series
     *
     * @param mixed series
     *
     * @return self
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }


    /**
     * Get the value of Banner Index
     *
     * @return mixed
     */
    public function getBannerIndex()
    {
        return $this->bannerIndex;
    }

    /**
     * Set the value of Banner Index
     *
     * @param mixed bannerIndex
     *
     * @return self
     */
    public function setBannerIndex($bannerIndex)
    {
        $this->bannerIndex = $bannerIndex;

        return $this;
    }



    /**
     * Get the value of Styling
     *
     * @return mixed
     */
    public function getStyling()
    {
        return $this->styling;
    }

    /**
     * Set the value of Styling
     *
     * @param mixed styling
     *
     * @return self
     */
    public function setStyling($styling)
    {
        $this->styling = $styling;

        return $this;
    }

}
