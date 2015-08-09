<?php

namespace TSProj\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Work_status
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TSProj\ProductBundle\Entity\WorkStatusRepository")
 */
class WorkStatus
{
    const status_havent = "ยังไม่เริ่มดำเนินงาน";
    const status_hold = "พักชั่วคราว";
    const status_in_progress = "กำลังดำเนินการ";
    const status_complete = "เสร็จสิ้น";
    
    public static $status_list = array(self::status_havent=>self::status_havent,
                                    self::status_hold=>self::status_hold,
                                    self::status_in_progress=>self::status_in_progress,
                                    self::status_complete=>self::status_complete);


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
     * @ORM\Column(name="status_name", type="string", length=255)
     */
    private $statusName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status_description", type="string", length=255)
     */
    private $statusDescription;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Project", mappedBy="projectStatus")
     **/
    private $project;
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="Product", mappedBy="productStatus")
     **/
    private $product;

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
     * @return Work_status
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
        $this->project = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->statusDescription;
    }

    /**
     * Add project
     *
     * @param \TSProj\ProductBundle\Entity\Project $project
     * @return WorkStatus
     */
    public function addProject(\TSProj\ProductBundle\Entity\Project $project)
    {
        $this->project[] = $project;
        $project->setProjectStatus($this);
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
     * Add product
     *
     * @param \TSProj\ProductBundle\Entity\Product $product
     * @return WorkStatus
     */
    public function addProduct(\TSProj\ProductBundle\Entity\Product $product)
    {
        $this->product[] = $product;
        $product->setProductStatus($this);
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
     * Set statusName
     *
     * @param string $statusName
     * @return WorkStatus
     */
    public function setStatusName($statusName)
    {
        $this->statusName = $statusName;

        return $this;
    }

    /**
     * Get statusName
     *
     * @return string 
     */
    public function getStatusName()
    {
        return $this->statusName;
    }
}
