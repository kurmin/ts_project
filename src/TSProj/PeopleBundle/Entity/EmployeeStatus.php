<?php

namespace TSProj\PeopleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeStatus
 *
 * @ORM\Table(name="employee_status")
 * @ORM\Entity(repositoryClass="TSProj\PeopleBundle\Entity\EmployeeStatusRepository")
 */
class EmployeeStatus
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
     * @ORM\Column(name="status_description", type="string", length=255)
     */
    private $statusDescription;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Employee", mappedBy="employeeStatus")
     **/
    private $employee;
    
    public function __toString() {
        return $this->statusDescription;
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
     * Set statusDescription
     *
     * @param string $statusDescription
     * @return EmployeeStatus
     */
    public function setStatusDescription($statusDescription)
    {
        $this->statusDescription = $statusDescription;

        return $this;
    }

    /**
     * Get statusDescription
     *
     * @return string 
     */
    public function getStatusDescription()
    {
        return $this->statusDescription;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add employee
     *
     * @param \TSProj\PeopleBundle\Entity\Employee $employee
     * @return EmployeeStatus
     */
    public function addEmployee(\TSProj\PeopleBundle\Entity\Employee $employee)
    {
        $this->employee[] = $employee;
        $employee->setEmployeeStatus($this);
        return $this;
    }

    /**
     * Remove employee
     *
     * @param \TSProj\PeopleBundle\Entity\Employee $employee
     */
    public function removeEmployee(\TSProj\PeopleBundle\Entity\Employee $employee)
    {
        $this->employee->removeElement($employee);
    }

    /**
     * Get employee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployee()
    {
        return $this->employee;
    }
}
