<?php

namespace TSProj\PeopleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="TSProj\PeopleBundle\Entity\ClientRepository")
 */
class Client
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
     * @ORM\Column(name="client_name", type="string", length=255)
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="client_contact_name", type="string", length=255)
     */
    private $clientContactName;

    /**
     * @var string
     *
     * @ORM\Column(name="client_address", type="string", length=255)
     */
    private $clientAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="client_tel_no_1", type="string", length=20)
     */
    private $clientTelNo1;

    /**
     * @var string
     *
     * @ORM\Column(name="client_tel_no_2", type="string", length=20,nullable=true)
     */
    private $clientTelNo2;
    
    /** 
     * @var boolean
     * 
     * @ORM\Column(name="delete_flag",type="boolean")
     */
    private $deleteFlag=0;
    
    /** 
     * @var datetime
     * 
     * @ORM\Column(name="last_maint_dt_time",type="datetime",nullable=true)
     */
    private $lastMaintDateTime;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="TSProj\ProductBundle\Entity\Project", mappedBy="client")
     **/
    private $project;

   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->project = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString() {
        return $this->clientName;
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
     * Set clientName
     *
     * @param string $clientName
     * @return Client
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string 
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set clientContactName
     *
     * @param string $clientContactName
     * @return Client
     */
    public function setClientContactName($clientContactName)
    {
        $this->clientContactName = $clientContactName;

        return $this;
    }

    /**
     * Get clientContactName
     *
     * @return string 
     */
    public function getClientContactName()
    {
        return $this->clientContactName;
    }

    /**
     * Set clientAddress
     *
     * @param string $clientAddress
     * @return Client
     */
    public function setClientAddress($clientAddress)
    {
        $this->clientAddress = $clientAddress;

        return $this;
    }

    /**
     * Get clientAddress
     *
     * @return string 
     */
    public function getClientAddress()
    {
        return $this->clientAddress;
    }

    /**
     * Set clientTelNo1
     *
     * @param string $clientTelNo1
     * @return Client
     */
    public function setClientTelNo1($clientTelNo1)
    {
        $this->clientTelNo1 = $clientTelNo1;

        return $this;
    }

    /**
     * Get clientTelNo1
     *
     * @return string 
     */
    public function getClientTelNo1()
    {
        return $this->clientTelNo1;
    }

    /**
     * Set clientTelNo2
     *
     * @param string $clientTelNo2
     * @return Client
     */
    public function setClientTelNo2($clientTelNo2)
    {
        $this->clientTelNo2 = $clientTelNo2;

        return $this;
    }

    /**
     * Get clientTelNo2
     *
     * @return string 
     */
    public function getClientTelNo2()
    {
        return $this->clientTelNo2;
    }

    /**
     * Add project
     *
     * @param \TSProj\ProductBundle\Entity\Project $project
     * @return Client
     */
    public function addProject(\TSProj\ProductBundle\Entity\Project $project)
    {
        $this->project[] = $project;
        $project->setClient($this);
        return $this;
    }

    /**
     * Remove project
     *
     * @param \TSProj\ProductBundle\Entity\Project $project
     */
    public function removeProject(\TSProj\ProductBundle\Entity\Project $project)
    {
        $this->project->removeElement($project);
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set deleteFlag
     *
     * @param boolean $deleteFlag
     * @return Client
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
     * @return Client
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
