<?php

namespace TSProj\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="TSProj\ProductBundle\Entity\StockRepository")
 */
class Stock
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="stock_product_name", type="string", length=255)
     */
    private $stockProductName;

    /**
     * @var string
     *
     * @ORM\Column(name="stock_product_description", type="string", length=255)
     */
    private $stockProductDescription;

    /**
     * @var float
     *
     * @ORM\Column(name="estimate_time", type="float")
     */
    private $estimateTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock_product_quantity", type="integer")
     */
    private $stockProductQuantity;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Product", mappedBy="stock")
     **/
    private $product;
    
    /** 
     * @var boolean
     * 
     * @ORM\Column(name="delete_flag",type="boolean")
     */
    private $deleteFlag=0;
    
    /** 
     * @var datetime
     * 
     * @ORM\Column(name="last_maint_dt_time",type="datetime")
     */
    private $lastMaintDateTime;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set stockProductName
     *
     * @param string $stockProductName
     * @return Stock
     */
    public function setStockProductName($stockProductName)
    {
        $this->stockProductName = $stockProductName;

        return $this;
    }

    /**
     * Get stockProductName
     *
     * @return string 
     */
    public function getStockProductName()
    {
        return $this->stockProductName;
    }

    /**
     * Set stockProductDescription
     *
     * @param string $stockProductDescription
     * @return Stock
     */
    public function setStockProductDescription($stockProductDescription)
    {
        $this->stockProductDescription = $stockProductDescription;

        return $this;
    }

    /**
     * Get stockProductDescription
     *
     * @return string 
     */
    public function getStockProductDescription()
    {
        return $this->stockProductDescription;
    }

    /**
     * Set estimateTime
     *
     * @param float $estimateTime
     * @return Stock
     */
    public function setEstimateTime($estimateTime)
    {
        $this->estimateTime = $estimateTime;

        return $this;
    }

    /**
     * Get estimateTime
     *
     * @return float 
     */
    public function getEstimateTime()
    {
        return $this->estimateTime;
    }

    /**
     * Set stockProductQuantity
     *
     * @param integer $stockProductQuantity
     * @return Stock
     */
    public function setStockProductQuantity($stockProductQuantity)
    {
        $this->stockProductQuantity = $stockProductQuantity;

        return $this;
    }

    /**
     * Get stockProductQuantity
     *
     * @return integer 
     */
    public function getStockProductQuantity()
    {
        return $this->stockProductQuantity;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() {
        return $this->stockProductName;
    }

    /**
     * Add product
     *
     * @param \TSProj\ProductBundle\Entity\Product $product
     * @return Stock
     */
    public function addProduct(\TSProj\ProductBundle\Entity\Product $product)
    {
        $this->product[] = $product;
        $product->setStock($this);
        return $this;
    }

    /**
     * Remove product
     *
     * @param \TSProj\ProductBundle\Entity\Product $product
     */
    public function removeProduct(\TSProj\ProductBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set deleteFlag
     *
     * @param boolean $deleteFlag
     * @return Stock
     */
    public function setDeleteFlag($deleteFlag)
    {
        $this->deleteFlag = $deleteFlag;

        return $this;
    }

    /**
     * Get deleteFlag
     *
     * @return boolean 
     */
    public function getDeleteFlag()
    {
        return $this->deleteFlag;
    }

    /**
     * Set lastMaintDateTime
     *
     * @param \DateTime $lastMaintDateTime
     * @return Stock
     */
    public function setLastMaintDateTime($lastMaintDateTime)
    {
        $dateTime = new \DateTime();
        if($lastMaintDateTime){
            $this->lastMaintDateTime = $lastMaintDateTime;
        }else{
            $this->lastMaintDateTime = $dateTime;
        }

        return $this;
    }

    /**
     * Get lastMaintDateTime
     *
     * @return \DateTime 
     */
    public function getLastMaintDateTime()
    {
        return $this->lastMaintDateTime;
    }
}
