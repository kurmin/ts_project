<?php

namespace TSProj\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Process
 *
 * @ORM\Table(name="process")
 * @ORM\Entity(repositoryClass="TSProj\ProductBundle\Entity\ProcessRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Process
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    //test upload to git

    /**
     * @var string
     *
     * @ORM\Column(name="process_barcode", type="string", length=45)
     */
    private $processBarcode;

    /**
     * @var string
     *
     * @ORM\Column(name="process_name", type="string", length=255)
     */
    private $processName;

    /**
     * 
     * @ORM\OneToMany(targetEntity="ProductProcessTime", mappedBy="process")
     **/
    private $productProcessTime;
    
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
     * 
     * @ORM\ManyToMany(targetEntity="Product",mappedBy="process",cascade={"PERSIST"})
     */
    private $product;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="Product", mappedBy="currentPhase")
     **/
    private $currentProduct;

    public function __toString() {
        return $this->processName;
    }
  
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
     * Set processBarcode
     *
     * @param string $processBarcode
     * @return process
     */
    public function setProcessBarcode($processBarcode)
    {
        $this->processBarcode = $processBarcode;

        return $this;
    }

    /**
     * Get processBarcode
     *
     * @return string 
     */
    public function getProcessBarcode()
    {
        return $this->processBarcode;
    }

    /**
     * Set processName
     *
     * @param string $processName
     * @return process
     */
    public function setProcessName($processName)
    {
        $this->processName = $processName;

        return $this;
    }

    /**
     * Get processName
     *
     * @return string 
     */
    public function getProcessName()
    {
        return $this->processName;
    }

    /**
     * Set deleteFlag
     *
     * @param boolean $deleteFlag
     * @return process
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
     * @return process
     */
    public function setLastMaintDateTime($lastMaintDateTime)
    {
        $this->lastMaintDateTime = $lastMaintDateTime;

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

    /**
     * Add productProcessTime
     *
     * @param \TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTime
     * @return process
     */
    public function addProductProcessTime(\TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTime)
    {
        $this->productProcessTime[] = $productProcessTime;

        return $this;
    }

    /**
     * Remove productProcessTime
     *
     * @param \TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTime
     */
    public function removeProductProcessTime(\TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTime)
    {
        $this->productProcessTime->removeElement($productProcessTime);
    }

    /**
     * Get productProcessTime
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductProcessTime()
    {
        return $this->productProcessTime;
    }

    /**
     * Add product
     *
     * @param \TSProj\ProductBundle\Entity\Product $product
     * @return process
     */
    public function addProduct(\TSProj\ProductBundle\Entity\Product $product)
    {
        $this->product[] = $product;

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
     * Constructor
     */
    public function __construct()
    {
        $this->productProcessTime = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->currentProduct = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add currentProduct
     *
     * @param \TSProj\ProductBundle\Entity\Product $currentProduct
     * @return Process
     */
    public function addCurrentProduct(\TSProj\ProductBundle\Entity\Product $currentProduct)
    {
        $this->currentProduct[] = $currentProduct;

        return $this;
    }

    /**
     * Remove currentProduct
     *
     * @param \TSProj\ProductBundle\Entity\Product $currentProduct
     */
    public function removeCurrentProduct(\TSProj\ProductBundle\Entity\Product $currentProduct)
    {
        $this->currentProduct->removeElement($currentProduct);
    }

    /**
     * Get currentProduct
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentProduct()
    {
        return $this->currentProduct;
    }
     /**
     *  @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setLastMaintDateTime(new \DateTime());
    }
}
