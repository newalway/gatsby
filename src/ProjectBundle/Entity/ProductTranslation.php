<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="product_translation", indexes={@ORM\Index(name="search_idx", columns={"title"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ProductTranslation
{
	use ORMBehaviors\Translatable\Translation;

	/**
	* @var string
	*
	* @ORM\Column(type="string", length=255)
	*/
	private $title;

	/**
    * @var string
    *
    * @ORM\Column(name="sub_title", type="string", length=255, nullable = true)
    */
    private $subTitle;

	/**
	* @var string
	*
	* @ORM\Column(name="short_desc", type="text", length=65535, nullable = true)
	*/
	private $shortDesc;

	/**
	* @var string
	*
	* @ORM\Column(type="text", length=65535, nullable = true)
	*/
	private $description;

	/**
	* @var string
	*
	* @ORM\Column(name="how_to_use", type="text", length=65535, nullable = true)
	*/
	private $howToUse;

	/**
	* @var string
	*
	* @ORM\Column(name="special_feature", type="text", length=65535, nullable = true)
	*/
	private $specialFeature;

	/**
	* @var string
	*
	* @ORM\Column(name="net_weight", type="text", length=65535, nullable = true)
	*/
	private $netWeight;

	/**
	* Get the value of Title
	*
	* @return string
	*/
	public function getTitle()
	{
		return $this->title;
	}

    /**
     * Set the value of Title
     *
     * @param string title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of Short Desc
     *
     * @return string
     */
    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    /**
     * Set the value of Short Desc
     *
     * @param string shortDesc
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of Description
     *
     * @param string description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of How To Use
     *
     * @return string
     */
    public function getHowToUse()
    {
        return $this->howToUse;
    }

    /**
     * Set the value of How To Use
     *
     * @param string howToUse
     *
     * @return self
     */
    public function setHowToUse($howToUse)
    {
        $this->howToUse = $howToUse;

        return $this;
    }

    /**
     * Get the value of Sub Title
     *
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * Set the value of Sub Title
     *
     * @param string subTitle
     *
     * @return self
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }


    /**
     * Get the value of Special Feature
     *
     * @return string
     */
    public function getSpecialFeature()
    {
        return $this->specialFeature;
    }

    /**
     * Set the value of Special Feature
     *
     * @param string specialFeature
     *
     * @return self
     */
    public function setSpecialFeature($specialFeature)
    {
        $this->specialFeature = $specialFeature;

        return $this;
    }

    /**
     * Get the value of Net Weight
     *
     * @return string
     */
    public function getNetWeight()
    {
        return $this->netWeight;
    }

    /**
     * Set the value of Net Weight
     *
     * @param string netWeight
     *
     * @return self
     */
    public function setNetWeight($netWeight)
    {
        $this->netWeight = $netWeight;

        return $this;
    }

}
