<?php

namespace TSProj\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProductProcessTimeAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('startDateTime')
            ->add('endDateTime')
            ->add('timeConsuming')
            ->add('deleteFlag')
            ->add('finishedFlag')
            ->add('lastMaintDateTime')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('startDateTime')
            ->add('endDateTime')
            ->add('timeConsuming')
            ->add('deleteFlag')
            ->add('finishedFlag')
            ->add('lastMaintDateTime')
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
            ->add('startDateTime')
            ->add('endDateTime')
            ->add('timeConsuming')
            ->add('deleteFlag')
            ->add('finishedFlag')
            ->add('lastMaintDateTime')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('startDateTime')
            ->add('endDateTime')
            ->add('timeConsuming')
            ->add('deleteFlag')
            ->add('finishedFlag')
            ->add('lastMaintDateTime')
        ;
    }
}
