<?php

namespace TSProj\PeopleBundle\Admin;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ClientAdmin extends \TSProj\ProductBundle\Admin\BaseAdmin
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
            ->add('clientName')
            ->add('clientContactName')
            ->add('clientAddress')
            ->add('clientTelNo1')    
            ->add('clientTelNo2')     
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('clientName')
            ->add('clientContactName')
            ->add('clientTelNo1')
            ->add('clientTelNo2')
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
            ->add('clientName')
            ->add('clientContactName')
            ->add('clientAddress')
            ->add('clientTelNo1')
            ->add('clientTelNo2',null,array('required'=>false))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('clientName')
            ->add('clientContactName')
            ->add('clientAddress')
            ->add('clientTelNo1')
            ->add('clientTelNo2')
        ;
    }
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('deleteRow', $this->getRouterIdParameter().'/deleteRow');
    }
}
