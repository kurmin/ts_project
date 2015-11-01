<?php

namespace TSProj\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="TSProj\ProductBundle\Entity\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="product_barcode", type="string", length=45)
     */
    private $productBarcode;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=255)
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="product_description", type="string", length=255)
     */
    private $productDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="drawing_id", type="string", length=255)
     */
    private $drawingId;
    
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media",cascade={"persist"},fetch="LAZY")
     */
    protected $drawingImage;

    /**
     * @var float
     *
     * @ORM\Column(name="product_time_consuming", type="float",nullable=true)
     */
    private $productTimeConsuming;
    
    /**
     * @var id
     * 
     * @ORM\Column(name="prev_project",type="integer",nullable=true)
     */
    private $prevProject = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="Project",inversedBy="product")
     **/
    private $project;
    
    /**
     * 
     * @ORM\ManyToMany(targetEntity="Process",inversedBy="product",cascade={"PERSIST"})
     */
    private $process;
    
    /**
     * @ORM\ManyToOne(targetEntity="WorkStatus",inversedBy="product")
     **/
    private $productStatus;
    
    /**
     * @ORM\ManyToOne(targetEntity="Stock",inversedBy="product")
     **/
    private $stock;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductProcessTime", mappedBy="product")
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
     * @var float
     * 
     * @ORM\Column(name="percent_finished",type="float")
     */
    private $percentFinished;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->process = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productProcessTime = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() {
        return $this->productName;
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
     * Set productBarcode
     *
     * @param string $productBarcode
     * @return Product
     */
    public function setProductBarcode($productBarcode)
    {
        $this->productBarcode = $productBarcode;

        return $this;
    }

    /**
     * Get productBarcode
     *
     * @return string 
     */
    public function getProductBarcode()
    {
        return $this->productBarcode;
    }

    /**
     * Set productName
     *
     * @param string $productName
     * @return Product
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string 
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set productDescription
     *
     * @param string $productDescription
     * @return Product
     */
    public function setProductDescription($productDescription)
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    /**
     * Get productDescription
     *
     * @return string 
     */
    public function getProductDescription()
    {
        return $this->productDescription;
    }

    /**
     * Set drawingId
     *
     * @param string $drawingId
     * @return Product
     */
    public function setDrawingId($drawingId)
    {
        $this->drawingId = $drawingId;

        return $this;
    }

    /**
     * Get drawingId
     *
     * @return string 
     */
    public function getDrawingId()
    {
        return $this->drawingId;
    }

    /**
     * Set productTimeConsuming
     *
     * @param float $productTimeConsuming
     * @return Product
     */
    public function setProductTimeConsuming($productTimeConsuming)
    {
        $this->productTimeConsuming = $productTimeConsuming;

        return $this;
    }

    /**
     * Get productTimeConsuming
     *
     * @return float 
     */
    public function getProductTimeConsuming()
    {
        return $this->productTimeConsuming;
    }

    /**
     * Set deleteFlag
     *
     * @param boolean $deleteFlag
     * @return Product
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
     * @return Product
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

    /**
     * Set project
     *
     * @param \TSProj\ProductBundle\Entity\Project $project
     * @return Product
     */
    public function setProject(\TSProj\ProductBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \TSProj\ProductBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add process
     *
     * @param \TSProj\ProductBundle\Entity\Process $process
     * @return Product
     */
    public function addProcess(\TSProj\ProductBundle\Entity\Process $process)
    {
        $this->process[] = $process;
        $process->setProduct($this);
        return $this;
    }

    /**
     * Remove process
     *
     * @param \TSProj\ProductBundle\Entity\Process $process
     */
    public function removeProcess(\TSProj\ProductBundle\Entity\Process $process)
    {
        $this->process->removeElement($process);
    }

    /**
     * Get process
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * Set productStatus
     *
     * @param \TSProj\ProductBundle\Entity\WorkStatus $productStatus
     * @return Product
     */
    public function setProductStatus(\TSProj\ProductBundle\Entity\WorkStatus $productStatus = null)
    {
        $this->productStatus = $productStatus;

        return $this;
    }

    /**
     * Get productStatus
     *
     * @return \TSProj\ProductBundle\Entity\WorkStatus 
     */
    public function getProductStatus()
    {
        return $this->productStatus;
    }

    /**
     * Set stock
     *
     * @param \TSProj\ProductBundle\Entity\Stock $stock
     * @return Product
     */
    public function setStock(\TSProj\ProductBundle\Entity\Stock $stock = null)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return \TSProj\ProductBundle\Entity\Stock 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Add productProcessTime
     *
     * @param \TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTime
     * @return Product
     */
    public function addProductProcessTime(\TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTime)
    {
        $this->productProcessTime[] = $productProcessTime;
        $productProcessTime->setProduct($this);
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
     * Set percentFinished
     *
     * @param float $percentFinished
     * @return Product
     */
    public function setPercentFinished($percentFinished)
    {
        $this->percentFinished = $percentFinished;

        return $this;
    }

    /**
     * Get percentFinished
     *
     * @return float 
     */
    public function getPercentFinished()
    {
        return $this->percentFinished;
    }

    /**
     * Set prevProject
     *
     * @param integer $prevProject
     * @return Product
     */
    public function setPrevProject($prevProject)
    {
        $this->prevProject = $prevProject;

        return $this;
    }

    /**
     * Get prevProject
     *
     * @return integer 
     */
    public function getPrevProject()
    {
        return $this->prevProject;
    }

    /**
     * Set drawingImage
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $drawingImage
     * @return Product
     */
    public function setDrawingImage(\Application\Sonata\MediaBundle\Entity\Media $drawingImage = null)
    {
        $this->drawingImage = $drawingImage;

        return $this;
    }

    /**
     * Get drawingImage
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getDrawingImage()
    {
        return $this->drawingImage;
    }
}
