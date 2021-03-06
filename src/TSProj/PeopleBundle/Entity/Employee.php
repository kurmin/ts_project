<?php

namespace TSProj\PeopleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="TSProj\PeopleBundle\Entity\EmployeeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Employee
{   
    public static $titleList = array('นาย'=>'นาย','นาง'=>'นาง','นางสาว'=>'นางสาว');
    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
     */
    protected $EmployeeImage;

    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="employeeID", type="integer")
     */       
    private $employeeId;
    
     /**
     * @var string
     *
     * @ORM\Column(name="employee_barcode", type="string", length=40)
     */        
    
    private $employeeBarcode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="employee_national_identity_id", type="string", length=20)
     */
    private $employeeNationalIdentityId;

     /**
     * @var string
     *
     * @ORM\Column(name="employee_title", type="string", length=20)
     */
    
    private $employeeTitle;
    
    /**
     * @var string
     *
     * @ORM\Column(name="employee_name", type="string", length=255)
     */
    
    private $employeeName;

    /**
     * @var string
     *
     * @ORM\Column(name="employee_surname", type="string", length=255)
     */
    private $employeeSurname;

    /**
     * @var string
     *
     * @ORM\Column(name="employee_address", type="string", length=255)
     */
    private $employeeAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="employee_tel_home", type="string", length=20,nullable=true)
     */
    private $employeeTelHome;

    /**
     * @var string
     *
     * @ORM\Column(name="employee_tel_mobile", type="string", length=20)
     */
    private $employeeTelMobile;

    /**
     * @var \Date
     *
     * @ORM\Column(name="employee_start_working_date", type="date")
     */
    private $employeeStartWorkingDate;
    
    /**
     * @var \Date
     *
     * @ORM\Column(name="employee_last_working_date", type="date",nullable=true)
     */
    private $employeelastWorkingDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="employee_role", type="string", length=20)
     */
    private $employeeRole;

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
     * @ORM\OneToMany(targetEntity="TSProj\ProductBundle\Entity\ProductProcessTime", mappedBy="employee")
     **/
    private $productProcessTime;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="TSProj\ProductBundle\Entity\ProductProcessTime", mappedBy="approvalEmployee")
     **/
    private $productProcessTimeApproval;
    
    /**
     * @ORM\ManyToOne(targetEntity="EmployeeStatus",inversedBy="employee")
     **/
    private $employeeStatus;
    
    /**
     * Get id
     *
     * @return integer 
     */
    
    
    public function getId()
    {
        return $this->id;
    }
    
        public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set employeeNationalIdentityId
     *
     * @param string $employeeNationalIdentityId
     * @return Employee
     */
    public function setEmployeeNationalIdentityId($employeeNationalIdentityId)
    {
        $this->employeeNationalIdentityId = $employeeNationalIdentityId;

        return $this;
    }

    /**
     * Get employeeNationalIdentityId
     *
     * @return string 
     */
    public function getEmployeeNationalIdentityId()
    {
        return $this->employeeNationalIdentityId;
    }

    /**
     * Set employeeName
     *
     * @param string $employeeName
     * @return Employee
     */
    public function setEmployeeName($employeeName)
    {
        $this->employeeName = $employeeName;

        return $this;
    }

    /**
     * Get employeeName
     *
     * @return string 
     */
    public function getEmployeeName()
    {
        return $this->employeeName;
    }

    /**
     * Set employeeSurname
     *
     * @param string $employeeSurname
     * @return Employee
     */
    public function setEmployeeSurname($employeeSurname)
    {
        $this->employeeSurname = $employeeSurname;

        return $this;
    }

    /**
     * Get employeeSurname
     *
     * @return string 
     */
    public function getEmployeeSurname()
    {
        return $this->employeeSurname;
    }

    public function getName()
    {
        return $this->employeeTitle.$this->employeeName." ".$this->employeeSurname; 
    }   
    
    public function __toString() {
        return $this->employeeName." ".$this->employeeSurname; 
    }
    
    /**
     * Set employeeAddress
     *
     * @param string $employeeAddress
     * @return Employee
     */
    public function setEmployeeAddress($employeeAddress)
    {
        $this->employeeAddress = $employeeAddress;

        return $this;
    }

    /**
     * Get employeeAddress
     *
     * @return string 
     */
    public function getEmployeeAddress()
    {
        return $this->employeeAddress;
    }

    /**
     * Set employeeTelHome
     *
     * @param string $employeeTelHome
     * @return Employee
     */
    public function setEmployeeTelHome($employeeTelHome)
    {
        $this->employeeTelHome = $employeeTelHome;

        return $this;
    }

    /**
     * Get employeeTelHome
     *
     * @return string 
     */
    public function getEmployeeTelHome()
    {
        return $this->employeeTelHome;
    }

    /**
     * Set employeeTelMobile
     *
     * @param string $employeeTelMobile
     * @return Employee
     */
    public function setEmployeeTelMobile($employeeTelMobile)
    {
        $this->employeeTelMobile = $employeeTelMobile;

        return $this;
    }

    /**
     * Get employeeTelMobile
     *
     * @return string 
     */
    public function getEmployeeTelMobile()
    {
        return $this->employeeTelMobile;
    }

    /**
     * Set employeeStartWorkingDate
     *
     * @param \DateTime $employeeStartWorkingDate
     * @return Employee
     */
    public function setEmployeeStartWorkingDate($employeeStartWorkingDate)
    {
        $this->employeeStartWorkingDate = $employeeStartWorkingDate;

        return $this;
    }

    /**
     * Get employeeStartWorkingDate
     *
     * @return \DateTime 
     */
    public function getEmployeeStartWorkingDate()
    {
        return $this->employeeStartWorkingDate;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productProcessTime = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productProcessTimeApproval = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add productProcessTime
     *
     * @param \TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTime
     * @return Employee
     */
    public function addProductProcessTime(\TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTime)
    {
        $this->productProcessTime[] = $productProcessTime;
        $productProcessTime->setEmployee($this);
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
     * Add productProcessTimeApproval
     *
     * @param \TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTimeApproval
     * @return Employee
     */
    public function addProductProcessTimeApproval(\TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTimeApproval)
    {
        $this->productProcessTimeApproval[] = $productProcessTimeApproval;

        return $this;
    }

    /**
     * Remove productProcessTimeApproval
     *
     * @param \TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTimeApproval
     */
    public function removeProductProcessTimeApproval(\TSProj\ProductBundle\Entity\ProductProcessTime $productProcessTimeApproval)
    {
        $this->productProcessTimeApproval->removeElement($productProcessTimeApproval);
    }

    /**
     * Get productProcessTimeApproval
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductProcessTimeApproval()
    {
        return $this->productProcessTimeApproval;
    }

    /**
     * Set deleteFlag
     *
     * @param boolean $deleteFlag
     * @return Employee
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
     * @return Employee
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
     * Set employeeStatus
     *
     * @param \TSProj\PeopleBundle\Entity\EmployeeStatus $employeeStatus
     * @return Employee
     */
    public function setEmployeeStatus(\TSProj\PeopleBundle\Entity\EmployeeStatus $employeeStatus = null)
    {
        $this->employeeStatus = $employeeStatus;

        return $this;
    }

    /**
     * Get employeeStatus
     *
     * @return \TSProj\PeopleBundle\Entity\EmployeeStatus 
     */
    public function getEmployeeStatus()
    {
        return $this->employeeStatus;
    }

    /**
     * Set employeelastWorkingDate
     *
     * @param \DateTime $employeelastWorkingDate
     * @return Employee
     */
    public function setEmployeelastWorkingDate($employeelastWorkingDate)
    {
        $this->employeelastWorkingDate = $employeelastWorkingDate;

        return $this;
    }

    /**
     * Get employeelastWorkingDate
     *
     * @return \DateTime 
     */
    public function getEmployeelastWorkingDate()
    {
        return $this->employeelastWorkingDate;
    }

    /**
     * Set employeeRole
     *
     * @param string $employeeRole
     * @return Employee
     */
    public function setEmployeeRole($employeeRole)
    {
        $this->employeeRole = $employeeRole;

        return $this;
    }

    /**
     * Get employeeRole
     *
     * @return string 
     */
    public function getEmployeeRole()
    {
        return $this->employeeRole;
    }

    /**
     * Set employeeBarcode
     *
     * @param string $employeeBarcode
     * @return Employee
     */
    public function setEmployeeBarcode($employeeBarcode)
    {
        $this->employeeBarcode = $employeeBarcode;

        return $this;
    }

    /**
     * Get employeeBarcode
     *
     * @return string 
     */
    public function getEmployeeBarcode()
    {
        return $this->employeeBarcode;
    }

    /**
     * Set EmployeeImage
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $employeeImage
     * @return Employee
     */
    public function setEmployeeImage(\Application\Sonata\MediaBundle\Entity\Media $employeeImage = null)
    {
        $this->EmployeeImage = $employeeImage;

        return $this;
    }

    /**
     * Get EmployeeImage
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getEmployeeImage()
    {
        return $this->EmployeeImage;
    }

    /**
     * Set employeeId
     *
     * @param integer $employeeId
     * @return Employee
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    /**
     * Get employeeId
     *
     * @return integer 
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * Set employee_title
     *
     * @param string $employeeTitle
     * @return Employee
     */
    public function setEmployeeTitle($employeeTitle)
    {
        $this->employeeTitle = $employeeTitle;

        return $this;
    }

    /**
     * Get employee_title
     *
     * @return string 
     */
    public function getEmployeeTitle()
    {
        return $this->employeeTitle;
    }
    public function updatedTimestamps()
    {
        $this->setLastMaintDateTime(new \DateTime());
    }
}
