<?php

namespace TSProj\ProductBundle\Admin;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductProcessTimeAdmin extends Admin 
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('product')
            ->add('process')
            ->add('employee')    
            ->add('finishedFlag')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('product')
            ->add('process')
            ->add('employee')    
            ->add('startDateTime', null, array('format' => 'Y-m-d H:i', 'timezone' => 'Asia/Bangkok'))
            ->add('endDateTime', null, array('format' => 'Y-m-d H:i', 'timezone' => 'Asia/Bangkok'))
            ->add('timeConsuming')
            ->add('finishedFlag')
            ->add('_action', 'actions', array(
                'actions' => array(
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
            ->add('product',null,array('read_only'=>true))
            ->add('process',null,array('read_only'=>true))
            ->add('employee',null,array('read_only'=>true))
            ->add('startDateTime','sonata_type_datetime_picker',array('required'=>false,'format' => 'dd/MM/yyyy HH:mm'))
            ->add('endDateTime','sonata_type_datetime_picker',array('required'=>false,'format' => 'dd/MM/yyyy HH:mm'))
            ->add('timeConsuming')
            ->add('finishedFlag','choice',array( 
                    'choices'  => array(0 => 'No', 1 => 'Yes'), 
                    'expanded'=>true,'multiple'=>false,'required'=>true,
                    'label'=>'Finished?'))
            ;
        /*
         *  public class keyClass extends EventDispatcher {
        $formMapper->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        $object= $event->getData();
        $form = $event->getForm();

        if (!$object || null === $object->getId()) 
        {
      
            $form
                ->add('product',null,array('required' => true,'disabled'  => false))
                ->add('process',null,array('required' => true,'disabled'  => false))
                ->add('employee',null,array('required' => true,'disabled'  => false));
        }
        });
         */
    }
}
