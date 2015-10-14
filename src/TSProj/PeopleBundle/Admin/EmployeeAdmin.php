<?php

namespace TSProj\PeopleBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class EmployeeAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('employeeId')
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
            ->add('employeeId')
            ->add('employee_title')
            ->add('employeeName')
            ->add('employeeSurname')
            ->add('employeeTelMobile')
            ->add('employeeRole')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
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
            ->add('employeeId')
            ->add('employeeNationalIdentityId')
            ->add('employee_title','choice',array('choices'=>  \TSProj\PeopleBundle\Entity\Employee::$titleList))
            ->add('employeeName')
            ->add('employeeSurname')
            ->add('employeeAddress')
            ->add('employeeTelHome')
            ->add('employeeTelMobile')
            ->add('employeeStartWorkingDate')
            ->add('employeelastWorkingDate')
            ->add('employeeRole')
           // ->add('EmployeeImage', 'file', array('label' => 'Employee Image', 'required' => false,  'data_class' => null))
            ->add('EmployeeImage', 'sonata_media_type', array(
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
            ->add('employeeId')
            ->add('employeeNationalIdentityId')
            ->add('employeeName')
            ->add('employeeSurname')
            ->add('employeeAddress')
            ->add('employeeTelHome')
            ->add('employeeTelMobile')
            ->add('employeeStartWorkingDate')
            ->add('employeelastWorkingDate')
            ->add('employeeRole')
            ->add('EmployeeImage','string', array('template' => 'PeopleBundle:Admin:Employee.html.twig'))
        ;
    }
}
