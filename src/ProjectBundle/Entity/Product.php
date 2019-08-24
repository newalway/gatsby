<?php

namespace ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Product
{
	use ORMBehaviors\Translatable\Translatable;

	/**
	   * @ORM\Column(type="integer")
	   * @ORM\Id
	   * @ORM\GeneratedValue(strategy="AUTO")
	   */
	private $id;

	/**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $sku;

	/**
	   * @ORM\Column(type="decimal", scale=2, nullable = true, options={"default":0})
	   */
	private $price;

	/**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $image;

	/**
	 * @ORM\Column(type="string", length=255, nullable = true)
	 */
	private $imageRadarChart;

	/** @ORM\Column(name="updated_at", type="datetime", nullable = true) */
	private $updatedAt;

	/** @ORM\Column(name="created_at", type="datetime") */
	private $createdAt;

	/**
     * @ORM\Column(type="smallint", options={"unsigned":true, "default":1})
     */
    private $status = 1;

	/** @ORM\Column(name="publish_date", type="datetime") */
	private $publishDate;

	/**
     * @ORM\Column(type="integer", options={"unsigned":true, "default":0})
     */
    private $position = 0;

	/**
	 * @ORM\Column(name="compare_at_price", type="decimal", scale=2, nullable = true, options={"default":0})
	 */
	private $compareAtPrice;

	/**
     * @ORM\Column(name="inventory_policy_status", type="smallint", options={"default":0})
     */
    private $inventoryPolicyStatus = 0;

	/**
     * @ORM\Column(name="inventory_quantity", type="integer", nullable = true)
     */
    private $inventoryQuantity;

	/**
     * @ORM\Column(type="decimal", scale=2, nullable = true, options={"default":0})
     */
    private $grams;

	/**
     * @ORM\Column(type="decimal", scale=2, nullable = true, options={"default":0})
     */
    private $weight;

	/**
     * @ORM\Column(name="weight_unit", type="string", length=45, nullable = true)
     */
    private $weightUnit;

	/**
     * Many Products have Many Hashtags.
     * @ORM\ManyToMany(targetEntity="Hashtag", inversedBy="products")
     * @ORM\JoinTable(name="products_hashtags")
     */
    private $hashtags;

	/**
     * Many Products have Many Showrooms.
     * @ORM\ManyToMany(targetEntity="Showroom", inversedBy="products")
     * @ORM\JoinTable(name="products_showrooms")
     */
    private $showrooms;

	/**
     * Many Products have Many Series.
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="products")
     * @ORM\JoinTable(name="products_series")
     */
    private $series;

	/**
     * Many Products have Many Discounts.
     * @ORM\ManyToMany(targetEntity="Discount", inversedBy="products")
     * @ORM\JoinTable(name="products_discounts")
     */
    private $discounts;

	/**
     * Many Products have Many Promotions.
     * @ORM\ManyToMany(targetEntity="Promotion", inversedBy="products")
     * @ORM\JoinTable(name="products_promotions")
     */
    private $promotions;

	/**
     * @ORM\OneToMany(targetEntity="Sku", mappedBy="product")
     */
    private $skus;

	/**
     * @ORM\OneToMany(targetEntity="Variant", mappedBy="product")
     */
    private $variants;

	/**
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product")
     */
    private $productImages;

	/**
	 * @ORM\OneToMany(targetEntity="ProductTechNique", mappedBy="product" , cascade={"persist"})
	 */
	private $productTechNique;


	/**
     * @ORM\Column(name="image_small", type="string", length=255, nullable = true)
     */
    private $imageSmall;

	/**
     * @ORM\Column(name="image_medium", type="string", length=255, nullable = true)
     */
    private $imageMedium;

	/**
     * @ORM\Column(name="image_large", type="string", length=255, nullable = true)
     */
    private $imageLarge;

	/**
     * @ORM\Column(name="user_weight", type="string", length=255, nullable = true)
     */
    private $userWeight;

	/**
     * @ORM\OneToMany(targetEntity="CustomerOrderItem", mappedBy="product")
     */
    private $customerOrderItems;

	/**
     * @ORM\Column(name="is_new", type="boolean", options={"unsigned":true, "default":0})
     */
    private $isNew = false;

	/**
     * @ORM\Column(name="is_show_weight",  type="smallint", options={"default":1})
     */
    private $isShowWeight = 1;

		/**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $imageInnerVideo;
	/**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $video;
    /**
    * @ORM\Column(name="video_id", type="string", length=255, nullable = true)
    */
    private $videoId;
    /**
     * @ORM\Column(name="video_width", type="smallint", nullable = true)
     */
    private $videoWidth;
    /**
     * @ORM\Column(name="video_height", type="smallint", nullable = true)
     */
    private $videoHeight;
    /**
     * @ORM\Column(name="video_duration", type="smallint", nullable = true)
     */
    private $videoDuration;
    /**
    * @ORM\Column(name="thumbnail_url", type="string", length=255, nullable = true)
    */
    private $thumbnailUrl;
    /**
     * @ORM\Column(name="thumbnail_width", type="smallint", nullable = true)
     */
    private $thumbnailWidth;
    /**
    * @ORM\Column(name="thumbnail_height", type="smallint", nullable = true)
    */
    private $thumbnailHeight;
    /**
    * @ORM\Column(name="thumbnail_url_play_button", type="string", length=255, nullable = true)
    */
    private $thumbnailUrlPlayButton;
    /**
    * @ORM\Column(name="video_embed", type="text", length=65535, nullable = true)
    */
    private $videoEmbed;

	public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

	public function __construct()
    {
		$this->showrooms = new ArrayCollection();
		$this->series = new ArrayCollection();
		$this->hashtags = new ArrayCollection();
		$this->skus = new ArrayCollection();
		$this->variants = new ArrayCollection();
		$this->productImages = new ArrayCollection();
        $this->productTechNique = new ArrayCollection();
		$this->discounts = new ArrayCollection();
		$this->promotions = new ArrayCollection();
		$this->customerOrderItems = new ArrayCollection();
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
	 * Get the value of Id
	 *
	 * @return mixed
	 */
	public function getId()
	{
	    return $this->id;
	}

	/**
	 * Set the value of Id
	 *
	 * @param mixed id
	 *
	 * @return self
	 */
	public function setId($id)
	{
	    $this->id = $id;

	    return $this;
	}

	/**
	 * Get the value of Price
	 *
	 * @return mixed
	 */
	public function getPrice()
	{
	    return $this->price;
	}

	/**
	 * Set the value of Price
	 *
	 * @param mixed price
	 *
	 * @return self
	 */
	public function setPrice($price)
	{
	    $this->price = $price;

	    return $this;
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
	 * Get the value of Created At
	 *
	 * @return mixed
	 */
	public function getCreatedAt()
	{
	    return $this->createdAt;
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
		$this->setImageSmall(null);
		$this->setImageMedium(null);
		$this->setImageLarge(null);
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
     * Get the value of Sku
     *
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set the value of Sku
     *
     * @param mixed sku
     *
     * @return self
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get the value of Variants
     *
     * @return mixed
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * Set the value of Variants
     *
     * @param mixed variants
     *
     * @return self
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;

        return $this;
    }

    /**
     * Get the value of Many Products have Many Hashtags.
     *
     * @return mixed
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }

    /**
     * Set the value of Many Products have Many Hashtags.
     *
     * @param mixed hashtags
     *
     * @return self
     */
    public function setHashtags($hashtags)
    {
        $this->hashtags = $hashtags;

        return $this;
    }

	/**
     * @param mixed hashtags
     */
    public function removeHashtags(Hashtag $hashtag)
    {
        if (false === $this->hashtags->contains($hashtag)) {
            return;
        }
        $this->hashtags->removeElement($hashtag);
        $hashtag->removeProducts($this);
    }

	/**
     * @param mixed hashtags
     */
    public function addHashtags(Hashtag $hashtag)
    {
        if (true === $this->hashtags->contains($hashtag)) {
            return;
        }
        $this->hashtags->add($hashtag);
        $hashtag->addProducts($this);
    }

    /**
     * Get the value of Skus
     *
     * @return mixed
     */
    public function getSkus()
    {
        return $this->skus;
    }

    /**
     * Set the value of Skus
     *
     * @param mixed skus
     *
     * @return self
     */
    public function setSkus($skus)
    {
        $this->skus = $skus;

        return $this;
    }


    /**
     * Get the value of Compare At Price
     *
     * @return mixed
     */
    public function getCompareAtPrice()
    {
        return $this->compareAtPrice;
    }

    /**
     * Set the value of Compare At Price
     *
     * @param mixed compareAtPrice
     *
     * @return self
     */
    public function setCompareAtPrice($compareAtPrice)
    {
        $this->compareAtPrice = $compareAtPrice;

        return $this;
    }

    /**
     * Get the value of Inventory Policy Status
     *
     * @return mixed
     */
    public function getInventoryPolicyStatus()
    {
        return $this->inventoryPolicyStatus;
    }

    /**
     * Set the value of Inventory Policy Status
     *
     * @param mixed inventoryPolicyStatus
     *
     * @return self
     */
    public function setInventoryPolicyStatus($inventoryPolicyStatus)
    {
        $this->inventoryPolicyStatus = $inventoryPolicyStatus;

        return $this;
    }

    /**
     * Get the value of Inventory Quantity
     *
     * @return mixed
     */
    public function getInventoryQuantity()
    {
        return $this->inventoryQuantity;
    }

    /**
     * Set the value of Inventory Quantity
     *
     * @param mixed inventoryQuantity
     *
     * @return self
     */
    public function setInventoryQuantity($inventoryQuantity)
    {
        $this->inventoryQuantity = $inventoryQuantity;

        return $this;
    }

    /**
     * Get the value of Grams
     *
     * @return mixed
     */
    public function getGrams()
    {
        return $this->grams;
    }

    /**
     * Set the value of Grams
     *
     * @param mixed grams
     *
     * @return self
     */
    public function setGrams($grams)
    {
        $this->grams = $grams;

        return $this;
    }

    /**
     * Get the value of Weight
     *
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the value of Weight
     *
     * @param mixed weight
     *
     * @return self
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get the value of Weight Unit
     *
     * @return mixed
     */
    public function getWeightUnit()
    {
        return $this->weightUnit;
    }

    /**
     * Set the value of Weight Unit
     *
     * @param mixed weightUnit
     *
     * @return self
     */
    public function setWeightUnit($weightUnit)
    {
        $this->weightUnit = $weightUnit;

        return $this;
    }

    /**
     * Get the value of Image Small
     *
     * @return mixed
     */
    public function getImageSmall()
    {
        return $this->imageSmall;
    }

    /**
     * Set the value of Image Small
     *
     * @param mixed imageSmall
     *
     * @return self
     */
    public function setImageSmall($imageSmall)
    {
        $this->imageSmall = $imageSmall;

        return $this;
    }

    /**
     * Get the value of Image Medium
     *
     * @return mixed
     */
    public function getImageMedium()
    {
        return $this->imageMedium;
    }

    /**
     * Set the value of Image Medium
     *
     * @param mixed imageMedium
     *
     * @return self
     */
    public function setImageMedium($imageMedium)
    {
        $this->imageMedium = $imageMedium;

        return $this;
    }

    /**
     * Get the value of Image Large
     *
     * @return mixed
     */
    public function getImageLarge()
    {
        return $this->imageLarge;
    }

    /**
     * Set the value of Image Large
     *
     * @param mixed imageLarge
     *
     * @return self
     */
    public function setImageLarge($imageLarge)
    {
        $this->imageLarge = $imageLarge;

        return $this;
    }


    /**
     * Get the value of Product Images
     *
     * @return mixed
     */
    public function getProductImages()
    {
        return $this->productImages;
    }

    /**
     * Set the value of Product Images
     *
     * @param mixed productImages
     *
     * @return self
     */
    public function setProductImages($productImages)
    {
        $this->productImages = $productImages;

        return $this;
    }


    /**
     * Get the value of Many Products have Many Showrooms.
     *
     * @return mixed
     */
    public function getShowrooms()
    {
        return $this->showrooms;
    }

    /**
     * Set the value of Many Products have Many Showrooms.
     *
     * @param mixed showrooms
     *
     * @return self
     */
    public function setShowrooms($showrooms)
    {
        $this->showrooms = $showrooms;

        return $this;
    }

	/**
     * @param mixed showrooms
     */
    public function removeShowrooms(Showroom $showroom)
    {
        if (false === $this->showrooms->contains($showroom)) {
            return;
        }
        $this->showrooms->removeElement($showroom);
        $showroom->removeProducts($this);
    }

	/**
     * @param mixed showrooms
     */
    public function addShowrooms(Showroom $showroom)
    {
        if (true === $this->showrooms->contains($showroom)) {
            return;
        }
        $this->showrooms->add($showroom);
        $showroom->addProducts($this);
    }


    /**
     * Get the value of User Weight
     *
     * @return mixed
     */
    public function getUserWeight()
    {
        return $this->userWeight;
    }

    /**
     * Set the value of User Weight
     *
     * @param mixed userWeight
     *
     * @return self
     */
    public function setUserWeight($userWeight)
    {
        $this->userWeight = $userWeight;

        return $this;
    }


    /**
     * Get the value of Publish Date
     *
     * @return mixed
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Set the value of Publish Date
     *
     * @param mixed publishDate
     *
     * @return self
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Get the value of Many Products have Many Discounts.
     *
     * @return mixed
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * Set the value of Many Products have Many Discounts.
     *
     * @param mixed discounts
     *
     * @return self
     */
    public function setDiscounts($discounts)
    {
        $this->discounts = $discounts;

        return $this;
    }

	/**
     * @param mixed discounts
     */
    public function removeDiscounts(Discount $discount)
    {
        if (false === $this->discounts->contains($discount)) {
            return;
        }
        $this->discounts->removeElement($discount);
        $discount->removeProducts($this);
    }

	/**
     * @param mixed discounts
     */
    public function addDiscounts(Discount $discount)
    {
        if (true === $this->discounts->contains($discount)) {
            return;
        }
        $this->discounts->add($discount);
        $discount->addProducts($this);
    }


    /**
     * Get the value of Many Products have Many Promotions.
     *
     * @return mixed
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

    /**
     * Set the value of Many Products have Many Promotions.
     *
     * @param mixed promotions
     *
     * @return self
     */
    public function setPromotions($promotions)
    {
        $this->promotions = $promotions;

        return $this;
    }

	/**
     * @param mixed promotions
     */
    public function removePromotions(Promotion $promotion)
    {
        if (false === $this->promotions->contains($promotion)) {
            return;
        }
        $this->promotions->removeElement($promotion);
        $promotion->removeProducts($this);
    }

	/**
     * @param mixed promotions
     */
    public function addPromotions(Promotion $promotion)
    {
        if (true === $this->promotions->contains($promotion)) {
            return;
        }
        $this->promotions->add($promotion);
        $promotion->addProducts($this);
    }


    /**
     * Get the value of Customer Order Items
     *
     * @return mixed
     */
    public function getCustomerOrderItems()
    {
        return $this->customerOrderItems;
    }

    /**
     * Set the value of Customer Order Items
     *
     * @param mixed customerOrderItems
     *
     * @return self
     */
    public function setCustomerOrderItems($customerOrderItems)
    {
        $this->customerOrderItems = $customerOrderItems;

        return $this;
    }

    /**
     * Get the value of Is New
     *
     * @return mixed
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set the value of Is New
     *
     * @param mixed isNew
     *
     * @return self
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }


    /**
     * Get the value of Many Products have Many Series.
     *
     * @return mixed
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set the value of Many Products have Many Series.
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

	// /**
    //  * @param mixed series
    //  */
    // public function removeSeries(Series $series)
    // {
    //     if (false === $this->series->contains($series)) {
    //         return;
    //     }
    //     $this->series->removeElement($series);
    //     $series->removeProducts($this);
    // }
    //
	// /**
    //  * @param mixed series
    //  */
    // public function addSeries(Series $series)
    // {
    //     if (true === $this->series->contains($series)) {
    //         return;
    //     }
    //     $this->series->add($series);
    //     $series->addProducts($this);
    // }


    /**
     * Get the value of Is Show Weight
     *
     * @return mixed
     */
    public function getIsShowWeight()
    {
        return $this->isShowWeight;
    }

    /**
     * Set the value of Is Show Weight
     *
     * @param mixed isShowWeight
     *
     * @return self
     */
    public function setIsShowWeight($isShowWeight)
    {
        $this->isShowWeight = $isShowWeight;

        return $this;
    }


    /**
     * Get the value of Video
     *
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set the value of Video
     *
     * @param mixed video
     *
     * @return self
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get the value of Video Id
     *
     * @return mixed
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    /**
     * Set the value of Video Id
     *
     * @param mixed videoId
     *
     * @return self
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;

        return $this;
    }

    /**
     * Get the value of Video Width
     *
     * @return mixed
     */
    public function getVideoWidth()
    {
        return $this->videoWidth;
    }

    /**
     * Set the value of Video Width
     *
     * @param mixed videoWidth
     *
     * @return self
     */
    public function setVideoWidth($videoWidth)
    {
        $this->videoWidth = $videoWidth;

        return $this;
    }

    /**
     * Get the value of Video Height
     *
     * @return mixed
     */
    public function getVideoHeight()
    {
        return $this->videoHeight;
    }

    /**
     * Set the value of Video Height
     *
     * @param mixed videoHeight
     *
     * @return self
     */
    public function setVideoHeight($videoHeight)
    {
        $this->videoHeight = $videoHeight;

        return $this;
    }

    /**
     * Get the value of Video Duration
     *
     * @return mixed
     */
    public function getVideoDuration()
    {
        return $this->videoDuration;
    }

    /**
     * Set the value of Video Duration
     *
     * @param mixed videoDuration
     *
     * @return self
     */
    public function setVideoDuration($videoDuration)
    {
        $this->videoDuration = $videoDuration;

        return $this;
    }

    /**
     * Get the value of Thumbnail Url
     *
     * @return mixed
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * Set the value of Thumbnail Url
     *
     * @param mixed thumbnailUrl
     *
     * @return self
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;

        return $this;
    }

    /**
     * Get the value of Thumbnail Width
     *
     * @return mixed
     */
    public function getThumbnailWidth()
    {
        return $this->thumbnailWidth;
    }

    /**
     * Set the value of Thumbnail Width
     *
     * @param mixed thumbnailWidth
     *
     * @return self
     */
    public function setThumbnailWidth($thumbnailWidth)
    {
        $this->thumbnailWidth = $thumbnailWidth;

        return $this;
    }

    /**
     * Get the value of Thumbnail Height
     *
     * @return mixed
     */
    public function getThumbnailHeight()
    {
        return $this->thumbnailHeight;
    }

    /**
     * Set the value of Thumbnail Height
     *
     * @param mixed thumbnailHeight
     *
     * @return self
     */
    public function setThumbnailHeight($thumbnailHeight)
    {
        $this->thumbnailHeight = $thumbnailHeight;

        return $this;
    }

    /**
     * Get the value of Thumbnail Url Play Button
     *
     * @return mixed
     */
    public function getThumbnailUrlPlayButton()
    {
        return $this->thumbnailUrlPlayButton;
    }

    /**
     * Set the value of Thumbnail Url Play Button
     *
     * @param mixed thumbnailUrlPlayButton
     *
     * @return self
     */
    public function setThumbnailUrlPlayButton($thumbnailUrlPlayButton)
    {
        $this->thumbnailUrlPlayButton = $thumbnailUrlPlayButton;

        return $this;
    }

    /**
     * Get the value of Video Embed
     *
     * @return mixed
     */
    public function getVideoEmbed()
    {
        return $this->videoEmbed;
    }

    /**
     * Set the value of Video Embed
     *
     * @param mixed videoEmbed
     *
     * @return self
     */
    public function setVideoEmbed($videoEmbed)
    {
        $this->videoEmbed = $videoEmbed;

        return $this;
    }


    /**
     * Get the value of Product Tech Nique
     *
     * @return mixed
     */
    public function getProductTechNique()
    {
        return $this->productTechNique;
    }

    /**
     * Set the value of Product Tech Nique
     *
     * @param mixed productTechNique
     *
     * @return self
     */
    public function setProductTechNique($productTechNique)
    {
        $this->productTechNique = $productTechNique;

        return $this;
    }


    /**
     * Get the value of Image Inner Video
     *
     * @return mixed
     */
    public function getImageInnerVideo()
    {
        return $this->imageInnerVideo;
    }

    /**
     * Set the value of Image Inner Video
     *
     * @param mixed imageInnerVideo
     *
     * @return self
     */
    public function setImageInnerVideo($imageInnerVideo)
    {
        $this->imageInnerVideo = $imageInnerVideo;

        return $this;
    }

	public function removeImageInnerVideo()
	{
		$this->setImageInnerVideo(null);
	}



    /**
     * Get the value of Image Radar Chart
     *
     * @return mixed
     */
    public function getImageRadarChart()
    {
        return $this->imageRadarChart;
    }

    /**
     * Set the value of Image Radar Chart
     *
     * @param mixed imageRadarChart
     *
     * @return self
     */
    public function setImageRadarChart($imageRadarChart)
    {
        $this->imageRadarChart = $imageRadarChart;

        return $this;
    }

    public function removeImageRadarChart()
    {
        $this->setImageRadarChart(null);
    }

}
