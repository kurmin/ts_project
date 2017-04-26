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
        
        $old_stock_time = 0;
        $old_product_time = 0;
        $product_time = 0;
        $new_product_time = 0;
        
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
                //update old stock
                $old_stock = $em->getRepository("TSProjProductBundle:Stock")->find($stockId);
                $old_stock->setStockProductQuantity($old_stock->getStockProductQuantity() + 1);
                $old_stock->setLastMaintDateTime($dateTime);
                
                //ลบเวลา stock เดิมออกจาก product 
                $old_stock_time = $old_stock->getEstimateTime();
                $old_product_time = $object->getProductTimeConsumingDays()*24 + $object->getProductTimeConsumingHours();
                $product_time = $old_product_time - $old_stock_time;
                
                $em->persist($old_stock);

           }
           
            $cur_stock = $object->getStock();
            if($cur_stock){
                $new_product_time = $product_time + $cur_stock->getEstimateTime();
                $result = $this->timeConsumingCalculation($new_product_time);
                
                //update time from new stock into product
                $object->setProductTimeConsumingDays($result[0]);
                $object->setProductTimeConsumingHours($result[1]);
                $object->setEstimatedTimeDay($result[0]);
                $object->setEstimatedTimeHour($result[1]);
                
                $qbs = $em->getRepository("TSProjProductBundle:WorkStatus")->createQueryBuilder('st')
                        ->where('st.statusName = :complete')
                        ->setParameter('complete', 'status_complete')
                        ->getQuery();
                $statusFinish= $qbs->getResult();
                $object->setProductStatus($statusFinish[0]);
                $object->setPercentFinished(100);
                
                $cur_stock->setStockProductQuantity($cur_stock->getStockProductQuantity() - 1);
                $cur_stock->setLastMaintDateTime($dateTime);
                
                $em->persist($cur_stock);
                
            }else{
                $qbs = $em->getRepository("TSProjProductBundle:WorkStatus")->createQueryBuilder('st')
                        ->where('st.statusName = :notstart')
                        ->setParameter('notstart', 'status_havent')
                        ->getQuery();
                $statusInProgress = $qbs->getResult();
                //$result2 = $this->timeConsumingCalculation($product_time);
                $object->setProductStatus($statusInProgress[0]);
                $object->setPercentFinished(0);
                //update time from new stock into product
                $object->setProductTimeConsumingDays(0);
                $object->setProductTimeConsumingHours(0);
                $object->setEstimatedTimeDay(0);
                $object->setEstimatedTimeHour(0);
            }     
        }
        
        //update last maintenance date time
        $object->setLastMaintDateTime($dateTime);
        $em->persist($object);
        
        $em->flush();
        
        return $object;
    }
    
    public function preUpdate($object) {
        parent::preUpdate($object);
        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $startDate = $object->getStartDateTime();
        $endDate = $object->getEndDateTime();
        if($endDate < $startDate){
            
        }
    }
    
//    public function validate(ErrorElement $errorElement, $object) {
//        $errorElement 
//            ->with('endDateTime')
//                ->assertGreaterThan('endDateTime','startDateTime')
//            ->end();    
//    }

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
            ->add('productStatus',null,array('choices'=> \TSProj\ProductBundle\Entity\WorkStatus::$status_list))  
            ->add('currentPhase')
            //->add('startDateTime', 'doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))
            //->add('endDateTime', 'doctrine_orm_datetime', array('field_type'=>'sonata_type_datetime_picker',))    
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper    
            ->add('project')    
            ->add('productBarcode')
            ->add('productName')
            ->add('productTimeConsuming',null,array('required'=>false,'read_only'=>true,'label'=>'Time Consuming','template'=>'TSProjProductBundle:Admin:list_time.html.twig'))         
	    ->add('productStatus')
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
        $qb = $this->getModelManager()->getEntityManager('TSProjProductBundle:Stock')->createQueryBuilder();
        $qb->select('s')
           ->from('TSProjProductBundle:Stock','s')
           ->where('s.deleteFlag=:no')
           ->andWhere('s.stockProductQuantity>=:one')     
           ->setParameter(':no',0)
           ->setParameter(':one',1); 
        
        $qb2 = $this->getModelManager()->getEntityManager('TSProjProductBundle:Project')->createQueryBuilder();
        $qb2->select('pj')
            ->from('TSProjProductBundle:Project','pj')
            ->where('pj.deleteFlag=:no')
            ->andWhere('pj.projectStatus<>:complete')
            ->setParameter(':no',0)
            ->setParameter(':complete',3);    
        
        $formMapper
            ->with('General',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))   
                    ->add('project','sonata_type_model',array(
                                    'query'=>$qb2,
                                    'empty_value'=>"--------- กรุณาเลือกโปรเจ็ค ---------",
                                    'required'=>true))     
                    ->add('productBarcode')
                    ->add('productName')
                    ->add('productDescription') 
					->add('material',null,array('required'=>false))	
                    ->add('productStatus',null,array('expanded'=>true,'multiple'=>false,'empty_value'=>false,'required'=>true))
                    ->add('stock','sonata_type_model',array(
                                'query'=>$qb,
                                'required'      =>false,
                                'empty_value'   =>'------ ไม่ผูกกับ Stock ------',))
            ->end()
            ->with('Process List',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))
                ->add('process',null,
                   array(  'expanded'  =>  true,
                           'multiple'  =>  true,
                           'required'  =>  true,
                           'empty_data' => 'ไม่มีงาน, รองานผลิต',
                        ))   
            ->end()   
            ->with('Performance',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))
                     ->add('currentPhase',null,array('required'=>false)) 
                                         ->add('estimatedTimeDay',null,array('required'=>false,'label'=>'Estimated Time (Day)'))
					 ->add('estimatedTimeHour',null,array('required'=>true,'label'=>'Estimated Time (Hour)'))
					 ->add('estimatedTimeMin',null,array('required'=>false,'label'=>'Estimated Time (Minute)'))
                     ->add('productTimeConsumingDays',null,array('required'=>false,'read_only'=>true))
                     ->add('productTimeConsumingHours',null,array('required'=>false,'read_only'=>true))
                     ->add('productTimeConsumingMins',null,array('required'=>false,'read_only'=>true))
                     ->add('percentFinished',null,array('required'=>false,'read_only'=>true,"label"=> 'Percent Finished (%)')) 
                     ->add('startDateTime','sonata_type_datetime_picker',array('required'=>false,'format' => 'dd/MM/yyyy HH:mm'))
                     ->add('endDateTime','sonata_type_datetime_picker',array('required'=>false,'format' => 'dd/MM/yyyy HH:mm', ))
            ->end()    
            ->with('Drawing',
                   array('class'       =>  'col-md-6',
                         'box_class'   =>  'box'))    
                    ->add('drawingId',null,array('required'=>false))
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
			->add('material')
            ->add('productStatus')    
            ->add('drawingId')
            ->add('drawingImage', 'string', array('template' => 'TSProjProductBundle:Admin:showImage.html.twig'))
            ->add('process')    
            ->add('currentPhase')  
                        ->add('estimatedTimeDay')
			->add('estimatedTimeHour')
			->add('estimatedTimeMin')
            ->add('startDateTime', null, array('format' => 'Y-m-d H:i', 'timezone' => 'Asia/Bangkok'))
            ->add('endDateTime', null, array('format' => 'Y-m-d H:i', 'timezone' => 'Asia/Bangkok'))
            ->add('productTimeConsuming',null,array('required'=>false,'read_only'=>true,'label'=>'Time Consuming','template'=>'TSProjProductBundle:Admin:show_time.html.twig'))         
            ->add('percentFinished','string',array('template'=>'TSProjProductBundle:Admin:show_progress.html.twig','label'=>'Percent Finidhed (%)'));
    }
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('deleteRow', $this->getRouterIdParameter().'/deleteRow')
                   ->remove('delete');
    }
}
