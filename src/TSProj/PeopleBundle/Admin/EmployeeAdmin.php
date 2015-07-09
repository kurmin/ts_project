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
            ->add('id')
            ->add('employeeNationalIdentityId')
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
            ->add('id')
            ->add('employeeNationalIdentityId')
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
            ->add('id')
            ->add('employeeNationalIdentityId')
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
            ->add('id')
            ->add('employeeNationalIdentityId')
            ->add('employeeName')
            ->add('employeeSurname')
            ->add('employeeAddress')
            ->add('employeeTelHome')
            ->add('employeeTelMobile')
            ->add('employeeStartWorkingDate')
            ->add('employeelastWorkingDate')
            ->add('employeeRole')
            ->add('EmployeeImage')
            ->add('Photo', 'String', array('template' => 'PeopleBundle:Admin:EmployeeImage.html.twig'))
        ;
    }
}
