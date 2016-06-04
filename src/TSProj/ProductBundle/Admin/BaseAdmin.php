<?php

namespace TSProj\ProductBundle\Admin;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Admin\Admin;

class BaseAdmin extends Admin
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
        try{
            parent::update($object);
            
            $em = $this->getModelManager()->getEntityManager($this->getClass());
            // Set date to today
            $dateTime = new \DateTime();
            //update last maintenance date time
            $object->setLastMaintDateTime($dateTime);
            $em->persist($object);
            $em->flush();
        }catch(\Doctrine\ORM\OptimisticLockException $e) {
            $this->getConfigurationPool()->getContainer()->get('session')
                    ->getFlashBag()->add('sonata_flash_error', 'someone modified the object in between');
        }
        
        return $object;
    }
    
    public function timeConsumingCalculation($Time_Hour){
        
        $TimeConsumingDay = 0;
        $TimeConsumingHour = 0;
        
        if ($Time_Hour < 24)
        {
            $TimeConsumingHour = $Time_Hour;
        }
        else 
        {
            $TimeConsumingDay = floor($Time_Hour/24);
            $TimeConsumingHour = $Time_Hour - $TimeConsumingDay*24;
        }
        return array($TimeConsumingDay, $TimeConsumingHour);
    }
}
