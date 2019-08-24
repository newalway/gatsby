<?php

namespace ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Series
 *
 * @ORM\Table(name="series")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\SeriesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Series
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
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $imageBanner;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $imageBannerMobile;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $template;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":0})
     */
    private $position = 0;

    /** @ORM\Column(name="updated_at", type="datetime", nullable = true) */
    private $updatedAt;

  	/** @ORM\Column(name="created_at", type="datetime") */
    private $createdAt;

    /**
    * @ORM\ManyToOne(targetEntity="ProductCategory", inversedBy="series")
    * @ORM\JoinColumn(name="product_category_id", referencedColumnName="id")
    */
    private $productCategory;

    /**
     * Many Series have Many Products.
     * @ORM\OneToMany(targetEntity="Product", mappedBy="series")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="Banner" , mappedBy="series")
     */
    private $bannerIndex;

    /**
     * @ORM\OneToMany(targetEntity="Styling" , mappedBy="series")
     */
    private $styling;

    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * Get the value of Many Series have Many Products.
     *
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of Many Series have Many Products.
     *
     * @param mixed products
     *
     * @return self
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @param mixed products
     */
    public function removeProducts(Product $product)
    {
        if (false === $this->products->contains($product)) {
            return;
        }
        $this->products->removeElement($product);
        $product->removeSeries($this);
    }

    /**
     * @param mixed products
     */
    public function addProducts(Product $product)
    {
        if (true === $this->products->contains($product)) {
            return;
        }
        $this->products->add($product);
        $product->addSeries($this);
    }


    /**
     * Get the value of Product Category
     *
     * @return mixed
     */
    public function getProductCategory()
    {
        return $this->productCategory;
    }

    /**
     * Set the value of Product Category
     *
     * @param mixed productCategory
     *
     * @return self
     */
    public function setProductCategory($productCategory)
    {
        $this->productCategory = $productCategory;

        return $this;
    }



    /**
     * Get the value of Image Banner
     *
     * @return mixed
     */
    public function getImageBanner()
    {
        return $this->imageBanner;
    }

    /**
     * Set the value of Image Banner
     *
     * @param mixed imageBanner
     *
     * @return self
     */
    public function setImageBanner($imageBanner)
    {
        $this->imageBanner = $imageBanner;

        return $this;
    }
   public function removeImageBanner()
     {
        $this->setImageBanner(null);
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
     * Get the value of Videos
     *
     * @return mixed
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set the value of Videos
     *
     * @param mixed videos
     *
     * @return self
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;

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


    /**
     * Get the value of Image Banner Mobile
     *
     * @return mixed
     */
    public function getImageBannerMobile()
    {
        return $this->imageBannerMobile;
    }

    /**
     * Set the value of Image Banner Mobile
     *
     * @param mixed imageBannerMobile
     *
     * @return self
     */
    public function setImageBannerMobile($imageBannerMobile)
    {
        $this->imageBannerMobile = $imageBannerMobile;

        return $this;
    }
    public function removeImageBannerMobile()
  {
     $this->setImageBannerMobile(null);
  }


    /**
     * Get the value of Template
     *
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set the value of Template
     *
     * @param mixed template
     *
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

}
