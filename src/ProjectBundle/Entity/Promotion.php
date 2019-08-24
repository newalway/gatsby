<?php

namespace ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promotion
 *
 * @ORM\Table(name="promotion")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\PromotionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Promotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $title;

    /**
    * @ORM\Column(type="string", length=255, nullable = true)
    */
    private $image;

    /**
    * @ORM\Column(name="short_desc", type="text", length=65535, nullable = true)
    */
    private $shortDesc;

    /**
    * @ORM\Column(type="text", length=65535, nullable = true)
    */
    private $description;

    /**
    * @ORM\Column(type="string", length=255, nullable = true)
    */
    private $filepath;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $filename;

    /**
    * @ORM\Column(type="decimal", scale=1, nullable=true)
    */
    private $filesize;

    /**
    * @ORM\Column(name="download_count", type="integer")
    */
    private $downloadCount = 0;

    /**
    * @ORM\Column(name="public_date", type="date", nullable=true)
    */
    private $publicDate;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status = 1;

    /** @ORM\Column(name="updated_at", type="datetime", nullable = true) */
	private $updatedAt;

    /** @ORM\Column(name="created_at", type="datetime") */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="PromotionDownloadCounter", mappedBy="promotion")
     */
    private $promotionDownloadCounter;

    /**
     * Many Promotions have Many Products.
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="promotions")
     */
    private $products;

    public function __construct()
    {
        $this->promotionDownloadCounter = new ArrayCollection();
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
     * Get the value of Title
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of Title
     *
     * @param mixed title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

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

    /**
     * Get the value of Short Desc
     *
     * @return mixed
     */
    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    /**
     * Set the value of Short Desc
     *
     * @param mixed shortDesc
     *
     * @return self
     */
    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;

        return $this;
    }

    /**
     * Get the value of Description
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of Description
     *
     * @param mixed description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of Filepath
     *
     * @return mixed
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Set the value of Filepath
     *
     * @param mixed filepath
     *
     * @return self
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get the value of Filename
     *
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of Filename
     *
     * @param mixed filename
     *
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get the value of Filesize
     *
     * @return mixed
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Set the value of Filesize
     *
     * @param mixed filesize
     *
     * @return self
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }

    /**
     * Get the value of Download Count
     *
     * @return mixed
     */
    public function getDownloadCount()
    {
        return $this->downloadCount;
    }

    /**
     * Set the value of Download Count
     *
     * @param mixed downloadCount
     *
     * @return self
     */
    public function setDownloadCount($downloadCount)
    {
        $this->downloadCount = $downloadCount;

        return $this;
    }

    /**
     * Get the value of Public Date
     *
     * @return mixed
     */
    public function getPublicDate()
    {
        return $this->publicDate;
    }

    /**
     * Set the value of Public Date
     *
     * @param mixed publicDate
     *
     * @return self
     */
    public function setPublicDate($publicDate)
    {
        $this->publicDate = $publicDate;

        return $this;
    }

    /**
     * Get the value of Status
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of Status
     *
     * @param mixed status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * Get the value of Promotion Download Counter
     *
     * @return mixed
     */
    public function getPromotionDownloadCounter()
    {
        return $this->promotionDownloadCounter;
    }

    /**
    * Set the value of Promotion Download Counter
    *
    * @param mixed promotionDownloadCounter
    *
    * @return self
    */
    public function setPromotionDownloadCounter($promotionDownloadCounter)
    {
        $this->promotionDownloadCounter = $promotionDownloadCounter;

        return $this;
    }

    public function removeImage()
    {
    	$this->setImage(null);
    }

    public function removeFilepath()
    {
    	$this->setFilepath(null);
        $this->setFilename(null);
        $this->setFilesize(null);
    }


    /**
     * Get the value of Many Promotions have Many Products.
     *
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of Many Promotions have Many Products.
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
        $product->removePromotions($this);
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
        $product->addPromotions($this);
    }


}
