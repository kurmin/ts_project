<?php

namespace TSProj\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ProjectAdmin extends Admin
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
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('projectName')
            ->add('projectStartDate','doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))
            ->add('projectEndDate','doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))
            ->add('projectStatus',null,array('choices'=> \TSProj\ProductBundle\Entity\WorkStatus::$status_list));
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('projectBarcode')
            ->add('projectName')
            ->add('orderDate') 
            ->add('amount')
            ->add('client')    
            ->add('projectDeliveryAddress')
            ->add('projectContactPhoneNo')
            ->add('expectDeliveryDate')
            ->add('currentPhase')    
            ->add('percentFinished','string',array('label'=>'Current Progress','template'=>'TSProjProductBundle:Admin:list_progress.html.twig'))     
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array('template' => 'TSProjProductBundle:CRUD:list__action_projectDeleteRow.html.twig',),
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
            ->tab('Project')    
                ->with('General',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))
                        ->add('projectBarcode')    
                        ->add('projectName')
                        ->add('projectStatus',null,array('expanded'=>true,'multiple'=>false,'empty_value'=>false,))
                ->end()    
                ->with('Contact Info',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box box-solid',))
                        ->add('client')      
                        ->add('projectDeliveryAddress','textarea')
                        ->add('projectContactPhoneNo')       
                ->end()
                ->with('Project Date',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))    
                        ->add('orderDate','sonata_type_date_picker') 
                        ->add('expectedDeliveryDate','sonata_type_date_picker')    
                        ->add('projectStartDate','sonata_type_date_picker')
                        ->add('projectEndDate','sonata_type_date_picker')
                ->end()    
                ->with('General',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))     
                        ->add('amount')    
                        ->add('projectTimeConsuming',null,array('required'=>false,'read_only'=>true))
                        ->add('currentPhase')    
                        ->add('percentFinished')
                ->end() 
            ->end() 
            ->tab('Product')    
                         ->add('product', 'sonata_type_collection',
                            array(),
                            array(
                                'edit' => 'inline',
                                'inline' => 'table',
                            )
                         )   
           ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('projectName')
            ->add('projectDeliveryAddress')
            ->add('projectContactPhoneNo')
            ->add('projectStartDate')
            ->add('projectEndDate')
            ->add('projectTimeConsuming')
//            ->with('Product', array('collapsed' => true))
//                ->add('child.name',null,array('label'=>'Name'))
//                ->add('child.school',null,array('label'=>'School'))
//                ->add('child.age',null,array('label'=>'Age')) 
//            ->end()    
            ->add('product')    
        ;
    }

//    public function configure()
//    {
//            $this->setTemplate("list", "TSProjProductBundle:Admin:list.html.twig");
//    }
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('projectDeleteRow', $this->getRouterIdParameter().'/projectDeleteRow');
    }
}
