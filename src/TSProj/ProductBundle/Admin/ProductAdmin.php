<?php

namespace TSProj\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ProductAdmin extends Admin
{
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere(
            $query->expr()->eq($query->getRootAliases()[0] . '.deleteFlag', ':not_delete')
        );
        $query->setParameter('not_delete', 0);
        return $query;
    }
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('productBarcode')
            ->add('productName')
            ->add('productDescription')
            ->add('productTimeConsuming')
            ->add('drawingId')
            ->add('drawingLocation')    
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper    
            ->add('id')
            ->add('productBarcode')
            ->add('productName')
            ->add('productDescription')
            ->add('productTimeConsuming')
            ->add('drawingId')
            ->add('drawingLocation')     
            ->add('process') 
            ->add('percentFinished','string',array('label'=>'Current Progress','template'=>'TSProjProductBundle:Admin:list_progress.html.twig'))    
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(
                            'template' => 'TSProjProductBundle:CRUD:list__action_deleteRow.html.twig',)
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
            ->with('General',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))   
                    ->add('project')     
                    ->add('productBarcode')
                    ->add('productName')
                    ->add('productDescription')
                    ->add('drawingId')
                    ->add('drawingLocation')     
                    ->add('productStatus',null,array('expanded'=>true,'multiple'=>false,'empty_value'=>false,))    
            ->end()
            ->with('Process List',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))
                ->add('process',null,
                   array(  'expanded'  =>  true,
                           'multiple'  =>  true,
                        ))   
            ->end()
            ->with('Performance',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))
                     ->add('stock')
                     ->add('productTimeConsuming',null,array('required'=>false,'read_only'=>true))
                     ->add('percentFinished') 
            ->end()    
        ;
    }

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        // Set date to today
        $dateTime = new \DateTime();

        // Instance points to the entity that is being created
        $instance->setLastMaintDateTime($dateTime);

        return $instance;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('productBarcode')
            ->add('productName')
            ->add('productDescription')
            ->add('productTimeConsuming')
            ->add('drawingId')
            ->add('drawingLocation') 
            ->add('process')       
        ;
    }
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('deleteRow', $this->getRouterIdParameter().'/deleteRow');
    }
}
