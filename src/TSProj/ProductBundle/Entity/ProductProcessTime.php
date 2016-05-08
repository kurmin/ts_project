<?php

namespace TSProj\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product_process_time
 *
 * @ORM\Table(name="product_process_time")
 * @ORM\Entity(repositoryClass="TSProj\ProductBundle\Entity\ProductProcessTimeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductProcessTime
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
     * @var \DateTime
     *
     * @ORM\Column(name="start_date_time", type="datetime")
     */
    private $startDateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date_time", type="datetime",nullable=true)
     */
    private $endDateTime;

    /**
     * @var float
     *
     * @ORM\Column(name="time_consuming", type="float",nullable=true)
     */
    private $timeConsuming=0;

    /**
     * @ORM\ManyToOne(targetEntity="Product",inversedBy="productProcessTime")
     **/
    protected $product;
    
    /**
     * @ORM\ManyToOne(targetEntity="Process",inversedBy="productProcessTime")
     **/
    protected $process;
    
    /**
     * @ORM\ManyToOne(targetEntity="TSProj\PeopleBundle\Entity\Employee",inversedBy="productProcessTime")
     **/
    protected $employee;
    
    /**
     * @ORM\ManyToOne(targetEntity="TSProj\PeopleBundle\Entity\Employee",inversedBy="productProcessTimeApproval")
     **/
    private $approvalEmployee;
    
    /** 
     * @var boolean
     * 
     * @ORM\Column(name="delete_flag",type="boolean")
     */
    private $deleteFlag=0;
    
    /** 
     * @var boolean
     * 
     * @ORM\Column(name="finished_flag",type="boolean")
     */
    private $finishedFlag=0;
    
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
     * Set startDateTime
     *
     * @param \DateTime $startDateTime
     * @return Product_process_time
     */
    public function setStartDateTime($startDateTime)
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    /**
     * Get startDateTime
     *
     * @return \DateTime 
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * Set endDateTime
     *
     * @param \DateTime $endDateTime
     * @return Product_process_time
     */
    public function setEndDateTime($endDateTime)
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    /**
     * Get endDateTime
     *
     * @return \DateTime 
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * Set timeConsuming
     *
     * @param float $timeConsuming
     * @return Product_process_time
     */
    public function setTimeConsuming($timeConsuming)
    {
        $this->timeConsuming = $timeConsuming;

        return $this;
    }

    /**
     * Get timeConsuming
     *
     * @return float 
     */
    public function getTimeConsuming()
    {
        return $this->timeConsuming;
    }

    /**
     * Set product
     *
     * @param \TSProj\ProductBundle\Entity\Product $product
     * @return ProductProcessTime
     */
    public function setProduct(\TSProj\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \TSProj\ProductBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set process
     *
     * @param \TSProj\ProductBundle\Entity\Process $process
     * @return ProductProcessTime
     */
    public function setProcess(\TSProj\ProductBundle\Entity\Process $process = null)
    {
        $this->process = $process;

        return $this;
    }

    /**
     * Get process
     *
     * @return \TSProj\ProductBundle\Entity\Process 
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * Set employee
     *
     * @param \TSProj\PeopleBundle\Entity\Employee $employee
     * @return ProductProcessTime
     */
    public function setEmployee(\TSProj\PeopleBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \TSProj\PeopleBundle\Entity\Employee 
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set approvalEmployee
     *
     * @param \TSProj\PeopleBundle\Entity\Employee $approvalEmployee
     * @return ProductProcessTime
     */
    public function setApprovalEmployee(\TSProj\PeopleBundle\Entity\Employee $approvalEmployee = null)
    {
        $this->approvalEmployee = $approvalEmployee;

        return $this;
    }

    /**
     * Get approvalEmployee
     *
     * @return \TSProj\PeopleBundle\Entity\Employee 
     */
    public function getApprovalEmployee()
    {
        return $this->approvalEmployee;
    }

    /**
     * Set deleteFlag
     *
     * @param boolean $deleteFlag
     * @return ProductProcessTime
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
     * Set finishedFlag
     *
     * @param boolean $finishedFlag
     * @return ProductProcessTime
     */
    public function setFinishedFlag($finishedFlag)
    {
        $this->finishedFlag = $finishedFlag;

        return $this;
    }

    /**
     * Get finishedFlag
     *
     * @return boolean 
     */
    public function getFinishedFlag()
    {
        return $this->finishedFlag;
    }

    /**
     * Set lastMaintDateTime
     *
     * @param \DateTime $lastMaintDateTime
     * @return ProductProcessTime
     */
    public function setLastMaintDateTime($lastMaintDateTime)
    {
        $dateTime = new \DateTime();
        if($lastMaintDateTime){
            $this->lastMaintDateTime = $lastMaintDateTime;
        }else{
            $this->lastMaintDateTime =  $dateTime;
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
     *  @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setLastMaintDateTime(new \DateTime());
    }
}
