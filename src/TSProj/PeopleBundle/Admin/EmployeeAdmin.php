<?php

namespace TSProj\PeopleBundle\Admin;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class EmployeeAdmin extends \TSProj\ProductBundle\Admin\BaseAdmin
{
   public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        // Set date to today
        $dateTime = new \DateTime();

        // Instance points to the entity that is being created
        $instance->setLastMaintDateTime($dateTime)
                 ->setDeleteFlag(0);

        return $instance;
    }
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('employeeId')
            ->add('employeeBarcode')    
            ->add('employeeNationalIdentityId')
            ->add('employee_title', 'doctrine_orm_choice', array(), 'choice' , array('choices' => \TSProj\PeopleBundle\Entity\Employee::$titleList))
            ->add('employeeName')
            ->add('employeeSurname')
            ->add('employeeRole') 
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('employeeBarcode')
            ->add('employee_title')
            ->add('employeeName')
            ->add('employeeSurname')
            ->add('employeeTelMobile')
            ->add('employeeRole')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array('template' => 'TSProjPeopleBundle:CRUD:list__action_deleteRow.html.twig'),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('employeeBarcode')    
            ->add('employeeNationalIdentityId')
            ->add('employee_title','choice',array('choices'=>  \TSProj\PeopleBundle\Entity\Employee::$titleList))
            ->add('employeeName')
            ->add('employeeSurname')
            ->add('employeeAddress')
            ->add('employeeTelHome',null,array('required'=>false))
            ->add('employeeTelMobile')
            ->add('employeeStartWorkingDate','sonata_type_date_picker',array('required'=>true,'format' => 'dd/MM/yyyy',))
            ->add('employeelastWorkingDate','sonata_type_date_picker',array('required'=>false,'format' => 'dd/MM/yyyy',))
            ->add('employeeRole')
            ->add('employeeStatus',null,array('expanded'=>true,'multiple'=>false,'empty_value'=>false,))    
           // ->add('EmployeeImage', 'file', array('label' => 'Employee Image', 'required' => false,  'data_class' => null))
            ->add('EmployeeImage', 'sonata_media_type', array(
                 'required' => false,
                 'provider' => 'sonata.media.provider.image',
                 'context'  => 'Employee'    
            ));
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('employeeBarcode')    
            ->add('employeeNationalIdentityId')
            ->add('name')    
            ->add('employeeAddress')
            ->add('employeeTelHome')
            ->add('employeeTelMobile')
            ->add('employeeStartWorkingDate')
            ->add('employeelastWorkingDate')
            ->add('employeeRole')
            ->add('EmployeeImage','string', array('template' => 'TSProjPeopleBundle:Admin:showImage.html.twig'))
            ->add('employeeStatus')    
        ;
    }
    
     protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('deleteRow', $this->getRouterIdParameter().'/deleteRow')
                   ->remove('delete');
    }
}
