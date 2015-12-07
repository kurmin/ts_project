<?php

namespace TSProj\ProductBundle\Admin;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ProjectAdmin extends BaseAdmin
{
    
   public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        // Set date to today
        $dateTime = new \DateTime();

        // Instance points to the entity that is being created
        $instance->setLastMaintDateTime($dateTime)
                 ->setPercentFinished(0)
                 ->setDeleteFlag(0)
                 ->setFinishedFlag(0);
        
        return $instance;
    }
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('projectBarcode')    
            ->add('projectName')
            ->add('client')    
            ->add('amount')    
            ->add('projectStatus',null,array('choices'=> \TSProj\ProductBundle\Entity\WorkStatus::$status_list))
            ->add('orderDate','doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))
            ->add('expectDeliveryDate','doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))    
            ->add('projectStartDate','doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))
            ->add('projectEndDate','doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))
            ->add('projectTimeConsuming')    
            ->add('percentFinished')    
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
            ->add('client')    
            ->add('projectContactPhoneNo')
            ->add('amount')    
            ->add('orderDate')     
            ->add('expectDeliveryDate') 
            ->add('projectTimeConsuming')    
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
                        ->add('client',null,array('empty_value'=>"--------- กรุณาเลือกชื่อลูกค้า ---------"))      
                        ->add('projectDeliveryAddress','textarea',array('required'=>false))
                        ->add('projectContactPhoneNo',null,array('required'=>false))       
                ->end()
                ->with('Project Date',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))    
                        ->add('orderDate','sonata_type_date_picker',array('format' => 'dd/MM/yyyy',)) 
                        ->add('expectedDeliveryDate','sonata_type_date_picker',array('required'=>true,'format' => 'dd/MM/yyyy', ))    
                        ->add('projectStartDate','sonata_type_date_picker',array('required'=>false,'format' => 'dd/MM/yyyy HH:mm',))
                        ->add('projectEndDate','sonata_type_date_picker',array('required'=>false,'format' => 'dd/MM/yyyy HH:mm',))
                ->end()    
                ->with('General',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))     
                        ->add('amount')    
                        ->add('projectTimeConsuming',null,array('required'=>false,'read_only'=>true))
                        ->add('percentFinished',null,array('required'=>false,))
                ->end() 
            ->end() 
//            ->tab('Product')    
//                         ->add('product', 'sonata_type_collection',
//                            array(),
//                            array(
//                                'edit' => 'inline',
//                                'inline' => 'table',
//                            )
//                         )   
//           ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('projectBarcode')
            ->add('projectName')
            ->add('projectStatus')    
            ->add('projectDeliveryAddress')
            ->add('projectContactPhoneNo')
            ->add('client')    
            ->add('amount')
            ->add('product')    
            ->add('orderDate')
            ->add('expectDeliveryDate')        
            ->add('projectStartDate')
            ->add('projectEndDate')  
            ->add('projectTimeConsuming') 
            ->add('percentFinished','string',array('template'=>'TSProjProductBundle:Admin:show_progress.html.twig'))      
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
