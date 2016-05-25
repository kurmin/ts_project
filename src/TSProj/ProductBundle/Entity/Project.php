<?php

namespace TSProj\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="TSProj\ProductBundle\Entity\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Project
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
     * @ORM\Column(name="project_name", type="string", length=255)
     */
    private $projectName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="project_detail", type="string", length=255,nullable=true)
     */
    private $projectDetail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="project_barcode", type="string", length=255)
     */
    private $projectBarcode;

    /**
     * @var float
     * 
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;
    
    /**
     * @var string
     *
     * @ORM\Column(name="project_delivery_address", type="string", length=255,nullable=true)
     */
    private $projectDeliveryAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="project_contact_phone_no", type="string", length=45,nullable=true)
     */
    private $projectContactPhoneNo;
 
    /**
     * @var \Date
     *
     * @ORM\Column(name="order_date", type="date")
     */
    private $orderDate;
    
    /**
     * @var \Date
     *
     * @ORM\Column(name="expected_delivery_date", type="date")
     */
    private $expectedDeliveryDate;
    
    /**
     * @var \Date
     *
     * @ORM\Column(name="project_start_date", type="date",nullable=true)
     */
    private $projectStartDate;

    /**
     * @var \Date
     *
     * @ORM\Column(name="project_end_date", type="date",nullable=true)
     */
    private $projectEndDate;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="time_consuming_days", type="integer",nullable=true)
     */
    private $timeConsumingDays=0;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="time_consuming_hours", type="integer",nullable=true)
     */
    private $timeConsumingHours=0;
    
     /**
     * @var integer
     * 
     * @ORM\Column(name="time_consuming_mins", type="integer",nullable=true)
     */
    private $timeConsumingMins=0;

    /**
     * @ORM\ManyToOne(targetEntity="TSProj\PeopleBundle\Entity\Client",inversedBy="project")
     **/
    private $client;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="Product", mappedBy="project")
     **/
    private $product;
    
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
     * @ORM\ManyToOne(targetEntity="WorkStatus",inversedBy="project")
     **/
    private $projectStatus;
    
    /**
     * @var float
     * 
     * @ORM\Column(name="percent_finished",type="float")
     */
    private $percentFinished;
    
    /**
     * @var string
     *
     * @ORM\Column(name="work_order_no", type="string", length=20)
     */
    private $workOrderNo;
    
    public function __toString() {
        return $this->projectName;
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
     * Set projectName
     *
     * @param string $projectName
     * @return Project
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return string 
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Set projectDeliveryAddress
     *
     * @param string $projectDeliveryAddress
     * @return Project
     */
    public function setProjectDeliveryAddress($projectDeliveryAddress)
    {
        $this->projectDeliveryAddress = $projectDeliveryAddress;

        return $this;
    }

    /**
     * Get projectDeliveryAddress
     *
     * @return string 
     */
    public function getProjectDeliveryAddress()
    {
        return $this->projectDeliveryAddress;
    }

    /**
     * Set projectContactPhoneNo
     *
     * @param string $projectContactPhoneNo
     * @return Project
     */
    public function setProjectContactPhoneNo($projectContactPhoneNo)
    {
        $this->projectContactPhoneNo = $projectContactPhoneNo;

        return $this;
    }

    /**
     * Get projectContactPhoneNo
     *
     * @return string 
     */
    public function getProjectContactPhoneNo()
    {
        return $this->projectContactPhoneNo;
    }

    /**
     * Set projectStartDate
     *
     * @param \DateTime $projectStartDate
     * @return Project
     */
    public function setProjectStartDate($projectStartDate)
    {
        $this->projectStartDate = $projectStartDate;

        return $this;
    }

    /**
     * Get projectStartDate
     *
     * @return \DateTime 
     */
    public function getProjectStartDate()
    {
        return $this->projectStartDate;
    }

    /**
     * Set projectEndDate
     *
     * @param \DateTime $projectEndDate
     * @return Project
     */
    public function setProjectEndDate($projectEndDate)
    {
        $this->projectEndDate = $projectEndDate;

        return $this;
    }

    /**
     * Get projectEndDate
     *
     * @return \DateTime 
     */
    public function getProjectEndDate()
    {
        return $this->projectEndDate;
    }

    /**
     * Set client
     *
     * @param \TSProj\PeopleBundle\Entity\Client $client
     * @return Project
     */
    public function setClient(\TSProj\PeopleBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \TSProj\PeopleBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \TSProj\ProductBundle\Entity\Product $product
     * @return Project
     */
    public function addProduct(\TSProj\ProductBundle\Entity\Product $product)
    {
        $this->product[] = $product;
        $product->setProject($this);
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
     * Set amount
     *
     * @param float $amount
     * @return Project
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     * @return Project
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime 
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set deleteFlag
     *
     * @param boolean $deleteFlag
     * @return Project
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
     * @return Project
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
     * @return Project
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
     * Set projectBarcode
     *
     * @param string $projectBarcode
     * @return Project
     */
    public function setProjectBarcode($projectBarcode)
    {
        $this->projectBarcode = $projectBarcode;

        return $this;
    }

    /**
     * Get projectBarcode
     *
     * @return string 
     */
    public function getProjectBarcode()
    {
        return $this->projectBarcode;
    }

    /**
     * Set projectStatus
     *
     * @param \TSProj\ProductBundle\Entity\WorkStatus $projectStatus
     * @return Project
     */
    public function setProjectStatus(\TSProj\ProductBundle\Entity\WorkStatus $projectStatus = null)
    {
        $this->projectStatus = $projectStatus;

        return $this;
    }

    /**
     * Get projectStatus
     *
     * @return \TSProj\ProductBundle\Entity\WorkStatus 
     */
    public function getProjectStatus()
    {
        return $this->projectStatus;
    }

    /**
     * Set expectedDeliveryDate
     *
     * @param \DateTime $expectedDeliveryDate
     * @return Project
     */
    public function setExpectedDeliveryDate($expectedDeliveryDate)
    {
        $this->expectedDeliveryDate = $expectedDeliveryDate;

        return $this;
    }

    /**
     * Get expectedDeliveryDate
     *
     * @return \DateTime 
     */
    public function getExpectedDeliveryDate()
    {
        return $this->expectedDeliveryDate;
    }

    /**
     * Set percentFinished
     *
     * @param float $percentFinished
     * @return Project
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
     * Set timeConsumingDays
     *
     * @param \int $timeConsumingDays
     * @return Project
     */
    public function setTimeConsumingDays($timeConsumingDays)
    {
        $this->timeConsumingDays = $timeConsumingDays;

        return $this;
    }

    /**
     * Get timeConsumingDays
     *
     * @return \int 
     */
    public function getTimeConsumingDays()
    {
        return $this->timeConsumingDays;
    }

    /**
     * Set timeConsumingHours
     *
     * @param \int $timeConsumingHours
     * @return Project
     */
    public function setTimeConsumingHours($timeConsumingHours)
    {
        $this->timeConsumingHours = $timeConsumingHours;

        return $this;
    }

    /**
     * Get timeConsumingHours
     *
     * @return \int 
     */
    public function getTimeConsumingHours()
    {
        return $this->timeConsumingHours;
    }

    /**
     * Set timeConsumingMins
     *
     * @param \int $timeConsumingMins
     * @return Project
     */
    public function setTimeConsumingMins($timeConsumingMins)
    {
        $this->timeConsumingMins = $timeConsumingMins;

        return $this;
    }

    /**
     * Get timeConsumingMins
     *
     * @return \int 
     */
    public function getTimeConsumingMins()
    {
        return $this->timeConsumingMins;
    }
    
    /**
     * 
     * @return string
     */
    public function getTimeConsuming()
    {
        return sprintf("%s,%s,%s",$this->getTimeConsumingDays(),$this->getTimeConsumingHours(),$this->getTimeConsumingMins());
    }        

    /**
     * Set workOrderNo
     *
     * @param string $workOrderNo
     * @return Project
     */
    public function setWorkOrderNo($workOrderNo)
    {
        $this->workOrderNo = $workOrderNo;

        return $this;
    }

    /**
     * Get workOrderNo
     *
     * @return string 
     */
    public function getWorkOrderNo()
    {
        return $this->workOrderNo;
    }

    /**
     * Set projectDetail
     *
     * @param string $projectDetail
     * @return Project
     */
    public function setProjectDetail($projectDetail)
    {
        $this->projectDetail = $projectDetail;
    
        return $this;
    }

    /**
     * Get projectDetail
     *
     * @return string 
     */
    public function getProjectDetail()
    {
        return $this->projectDetail;
    }
         /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setLastMaintDateTime(new \DateTime());
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedPercentFinished()
    {
        if($this->projectStatus == 'เสร็จสิ้น' && $this->percentFinished < 100)
        {
            $this->setPercentFinished(100);
        }
    }
}
