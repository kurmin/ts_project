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
        
        if($object->getStock() !== $original['stock']) {
           //get stock id
           $stockId = "";
           $pos = 0;
           while(!ctype_space(substr($original['stock'], $pos, 1))){
               $stockId .= substr($original['stock'], $pos, 1);
               $pos++;
           } 
           $old = $em->getRepository("TSProjProductBundle:Stock")->find($stockId);
           $this->updateStock($object,$old);
        }
        
        return $object;
    }
    
    private function updateStock($object,$original){
        /* @var $object \TSProj\ProductBundle\Entity\Product  */
        /* @var $original \TSProj\ProductBundle\Entity\Product */
        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $original->setStockProductQuantity($original->getStockProductQuantity() + 1);
        $new_stock = $object->getStock();
        if($new_stock){
            $new_stock->setStockProductQuantity($new_stock->getStockProductQuantity() - 1);
            $object->setProductTimeConsuming($new_stock->getEstimateTime());
            $em->persist($new_stock);
        }else{
            $object->setProductTimeConsuming(0);
        }
        $em->persist($object);
        $em->persist($original);
        
        $em->flush();
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function 
            configureDatagridFilters(DatagridMapper $datagridMapper)
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
                     ->add('stock',null,array('required'=>false,'empty_value'=>'------ ไม่ผูกกับ Stock ------'))
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
