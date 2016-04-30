<?php

namespace TSProj\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends BaseController
{
    /**
     * @Route("/test")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository("TSProjProductBundle:Product")->find(1);//insert id product into ()
        
        if (count($product) > 0)
        {
        $ppts = $product->getProductProcessTime(); //get productProcessTime by product id, result is in array
        
        //count number of product process time
        $qb = $em->createQueryBuilder();
        $qb->select('count(p.id)')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->innerJoin('p.product', 'pd')      
           ->where('pd.id = :productId')     
           ->setParameter('productId', '1');
        $cntPpt = $qb->getQuery()->getSingleScalarResult(); 
        
        //--------- nok codes here ---------------------------
        if($cntPpt > 0){
            
        echo "Total rows in product_process_time table : ".$cntPpt."<br/>";
            
        $TimeConsuming = 0;   
            foreach($ppts as $item){
                $TimeConsumingA = $item->getTimeConsuming();
                $TimeConsuming = $TimeConsuming + $TimeConsumingA;
                
                echo $TimeConsumingA . " + ";
        }
        $result = $this->timeConsumingCalculation($TimeConsuming);
        $product->setProductTimeConsumingDays($result[0]);
        $product->setProductTimeConsumingHours($result[1]);
        $product->setProductTimeConsumingMins($result[2]);
        
        echo "Total time consuming : ".$TimeConsuming."<br/>";
        echo "Total time consuming (DAY) : ".$result[0]."<br/>";
        echo "Total time consuming (HOUR) : ".$result[1]."<br/>";
        echo "Total time consuming (MIN) : ".$result[2]."<br/>";
        
        $esDay = $product->getEstimatedTimeDay();
        $esHour = $product->getEstimatedTimeHour();
        $esMin = $product->getEstimatedTimeMin();
        $resultPercent = $this->percentFinishedCalculation($TimeConsuming, $esDay, $esHour, $esMin);
        $product-> setPercentFinished($resultPercent);
        
        echo $resultPercent."%";
        
        //update product start date and end date time
       
        $qb2 = $em->createQueryBuilder();
        $qb2->select('p')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->innerJoin('p.product', 'pd')      
           ->where('pd.id = :productId')     
           ->setParameter('productId', '1')
           ->orderBy('p.startDateTime');  
        $products = $qb2->getQuery()->getResult();
        $product->setStartDateTime($products[0]->getStartDateTime());
        
        $qb3 = $em->createQueryBuilder();
        $qb3->select('p')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->innerJoin('p.product', 'pd')      
           ->where('pd.id = :productId')     
           ->setParameter('productId', '1')
           ->orderBy('p.endDateTime', 'desc');  
        $products = $qb3->getQuery()->getResult();
        $product->setEndDateTime($products[0]->getEndDateTime());
        
       $em->persist($product);
       $em->flush();
        die();
        }
        }
   
         // add parameter by yourself
             
        
//        $query = $em->getRepository('TSProjProductBundle:ProductProcessTime')->createQueryBuilder('p');
//        $query->select('p')
//              ->groupBy('p.product')
//              ->orderBy('p.startDateTime');  
//        $productStartDate = $query->getQuery()->getResult();
        echo "ggggg"; 
//        echo date_format($productStartDate[0]->getStartDateTime(), 'Y-m-d'); die();
        echo date_format($product->getStartDateTime(),'Y-m-d'); die();
        $proCnt = $product->getProductProcessTime();
        \Doctrine\Common\Util\Debug::dump($proCnt);
        return array();
    }
}
