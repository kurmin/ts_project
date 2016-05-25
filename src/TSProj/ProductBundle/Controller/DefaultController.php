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
        
        //count distinct number of processes that have been finished
        
        $qb = $em->createQueryBuilder();
        $qb->select('count( distinct p.process)')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->innerJoin('p.product', 'pd')      
           ->where('pd.id = :productId') 
           ->andWhere('p.finishedFlag = 1')     
           ->setParameter('productId', '1');
        $finishedCount = $qb->getQuery()->getSingleScalarResult(); 
        
        echo "No. of processes that have been finished:".$finishedCount."<br/>";
        
        //$dt = new DateTime('2012-03-11 3:00AM');
        //echo $dt->format('Ymdh') . "\n";
        
        //check whether all processes have been finished or not
        
        $noOfProcess =  $product->getNoOfProcess();
        $ProductStatus = $product->getProductStatus();
        
        echo $ProductStatus;
        
        $finished = 0;
        
        if ($ProductStatus == 'เสร็จสิ้น')
        {
            $finished = 1;
        }     
        elseif($finishedCount ==  $noOfProcess)
        {
            $finished = 1;
            
            //update product status when all processes are finished
            $product->setProductStatus('เสร็จสิ้น');
        }
         
        $resultPercent = $this->percentFinishedCalculation($TimeConsuming, $esDay, $esHour, $esMin, $finished);
        $product-> setPercentFinished($resultPercent);
        
        echo $resultPercent."% <br/>";
        
        
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
        
        //Sum project time consuming       
        
        $qb = $em->createQueryBuilder();
        $qb->select('pj.id')
           ->from('TSProjProductBundle:Product','pd')
           ->innerJoin('pd.project', 'pj')   
           ->where('pd.id = :productId')     
           ->setParameter('productId', '1');
        $projectId = $qb->getQuery()->getSingleScalarResult(); 
        
        echo $projectId."<br/>";
        
        $em = $this->getDoctrine()->getEntityManager();
        $project = $em->getRepository("TSProjProductBundle:Project")->find($projectId);//insert id product into ()
        
        echo $project->getProjectName()."<br/>";
        
        $pdts = $project->getProduct();
        $projTimeConsuming = 0;
        $Consume_Day = 0;
        $Consume_Hour = 0;
        $Consume_Min = 0;
        $projEsDay = 0;
        $projEsHour = 0;
        $projEsMin = 0;
                
        foreach($pdts as $item2){

                $Consume_Day = $item2->getProductTimeConsumingDays();
                $Consume_Hour = $item2->getProductTimeConsumingHours();
                $Consume_Min = $item2->getProductTimeConsumingMins();
                
                $projEsDay = $item2->getEstimatedTimeDay();
                $projEsHour =  $item2->getEstimatedTimeHour();
                $ProjEsMin =  $item2->getEstimatedTimeMin();
                
                $projTimeConsuming = $projTimeConsuming + ($Consume_Day * 24 * 60) + ($Consume_Hour * 60) + $Consume_Min;
        }

        $resultProj = $this->timeConsumingCalculation($projTimeConsuming);
        $project->setTimeConsumingDays($resultProj[0]);
        $project->setTimeConsumingHours($resultProj[1]);
        $project->setTimeConsumingMins($resultProj[2]);
        
        echo "Total time consuming : ".$projTimeConsuming."<br/>";
        echo "Total time consuming (DAY) : ".$resultProj[0]."<br/>";
        echo "Total time consuming (HOUR) : ".$resultProj[1]."<br/>";
        echo "Total time consuming (MIN) : ".$resultProj[2]."<br/>";
        
        //Count number of products in this project
        
        $qb = $em->createQueryBuilder();
        $qb->select('count(distinct pd.id)')
           ->from('TSProjProductBundle:Product','pd')
           ->innerJoin('pd.project', 'pj')   
           ->where('pj.id = :projectId')     
           ->setParameter('projectId', $projectId);
        $noOfProducts = $qb->getQuery()->getSingleScalarResult(); 

        echo "no Of Products = ". $noOfProducts."<br/>";
        
        //Count number of products in this project which already finished
        
        $qb = $em->createQueryBuilder();
        $qb->select('count(distinct pd.id)')
           ->from('TSProjProductBundle:Product','pd')
           ->innerJoin('pd.project', 'pj')   
           ->innerJoin('pd.productStatus', 's')  
           ->where('pj.id = :projectId')  
           ->andWhere('s.id = 4')
           ->setParameter('projectId', $projectId);
        $noOfFinishedProducts = $qb->getQuery()->getSingleScalarResult(); 
        
        echo "no Of finished Products = ". $noOfFinishedProducts."<br/>";
        
        $projectFinished = 0;
        $ProjectStatus = $project->getProjectStatus();
      
        echo $ProjectStatus."% <br/>";
        
        if ($ProjectStatus == 'เสร็จสิ้น')
        {
            $projectFinished = 1;
        }     
        else
        if ($noOfFinishedProducts ==  $noOfProducts)
        {
            $projectFinished = 1;
        }
                 
        $projPercent = $this->percentFinishedCalculation($projTimeConsuming, $projEsDay, $projEsHour, $projEsMin, $projectFinished);
        $project-> setPercentFinished($projPercent);
        
        echo $projPercent."% <br/>";
        
       $em->persist($project); 
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
