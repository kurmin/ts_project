<?php

namespace TSProj\ProductBundle\Admin;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ProductAdmin extends BaseAdmin
{
    
    public function update($object)
    {
        $this->preUpdate($object);
        
        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $original = $em->getUnitOfWork()->getOriginalEntityData($object);
        
        foreach ($this->extensions as $extension) {
            $extension->preUpdate($this, $object);
        }

        $result = $this->getModelManager()->update($object);
        // BC compatibility
        if (null !== $result) {
            $object = $result;
        }
           
        $this->postUpdate($object);
        foreach ($this->extensions as $extension) {
            $extension->postUpdate($this, $object);
        }
        
        // Set date to today
        $dateTime = new \DateTime();
        
        if($object->getStock() !== $original['stock']) {
           //get stock id
           //stock layout -> [id] - [stockProductName] | Time: [estimateTime (in hour)]
           $stockId = "";
           if(!is_null($original['stock'])){
                $pos = 0;
                while(!ctype_space(substr($original['stock'], $pos, 1))){
                    $stockId .= substr($original['stock'], $pos, 1);
                    $pos++;
                }
                $old = $em->getRepository("TSProjProductBundle:Stock")->find($stockId);
                $old->setStockProductQuantity($old->getStockProductQuantity() + 1);
                $old->setLastMaintDateTime($dateTime);
                $object->setProductTimeConsuming($original['productTimeConsuming']-$old->getEstimateTime());
                $em->persist($old);
           }
           $this->updateStock($object);
        }
        
        //update last maintenance date time
        $object->setLastMaintDateTime($dateTime);
        $em->persist($object);
        
        $em->flush();
        
        return $object;
    }
    
    private function updateStock($object){
        /* @var $object \TSProj\ProductBundle\Entity\Product  */
        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $new_stock = $object->getStock();
        $curr = new \DateTime();
        if($new_stock){
            $new_stock->setStockProductQuantity($new_stock->getStockProductQuantity() - 1);
            $new_stock->setLastMaintDateTime($curr);
            $object->setProductTimeConsuming($object->getProductTimeConsuming() + $new_stock->getEstimateTime());
            $em->persist($new_stock);
        }else{
            $object->setProductTimeConsuming(0);
        }
        $em->persist($object);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function 
            configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('project')    
            ->add('productBarcode')
            ->add('productName')
            ->add('productDescription')
            ->add('productTimeConsuming')
            ->add('drawingId')
            ->add('stock')
            ->add('productStatus',null,array('choices'=> \TSProj\ProductBundle\Entity\WorkStatus::$status_list))  
            ->add('percentFinished')
            ->add('currentPhase')
            ->add('startDateTime', 'doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))
            ->add('endDateTime', 'doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))    
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper    
            ->add('productBarcode')
            ->add('productName')
            ->add('productTimeConsuming')
            ->add('drawingId')     
            ->add('currentPhase') 
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
                    ->add('project',null,array('empty_value'=>"--------- กรุณาเลือกโปรเจ็ค ---------"))     
                    ->add('productBarcode')
                    ->add('productName')
                    ->add('productDescription')     
                    ->add('productStatus',null,array('expanded'=>true,'multiple'=>false,'empty_value'=>false,'required'=>true))
                    ->add('stock',null,array('required'=>false,'empty_value'=>'------ ไม่ผูกกับ Stock ------'))
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
                     ->add('currentPhase',null,array('required'=>false))        
                     ->add('productTimeConsuming',null,array('required'=>false,'read_only'=>true))
                     ->add('percentFinished',null,array('required'=>false,'read_only'=>true)) 
                     ->add('startDateTime','sonata_type_datetime_picker',array('required'=>false,'format' => 'dd/MM/yyyy HH:mm'))
                     ->add('endDateTime','sonata_type_datetime_picker',array('required'=>false,'format' => 'dd/MM/yyyy HH:mm', ))
            ->end()    
            ->with('Drawing',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))    
                    ->add('drawingId')
                    ->add('drawingImage','sonata_media_type', array(
                          'provider' => 'sonata.media.provider.image',
                          'context'  => 'product',
                          'required' => false,
                         ))
            ->end()    
        ;
    }

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        // Set date to today
        $dateTime = new \DateTime();

        // Instance points to the entity that is being created
        $instance->setLastMaintDateTime($dateTime)
                 ->setPercentFinished(0)
                 ->setDeleteFlag(0);
        
        return $instance;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('project')
            ->add('productBarcode')
            ->add('productName')
            ->add('productDescription')
            ->add('productStatus')    
            ->add('drawingId')
            ->add('drawingImage', 'string', array('template' => 'TSProjProductBundle:Admin:showImage.html.twig'))
            ->add('process')    
            ->add('currentPhase')  
            ->add('startDateTime')
            ->add('endDateTime')
            ->add('productTimeConsuming')        
            ->add('percentFinished','string',array('template'=>'TSProjProductBundle:Admin:show_progress.html.twig'))    
        ;
    }
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('deleteRow', $this->getRouterIdParameter().'/deleteRow');
    }
}
