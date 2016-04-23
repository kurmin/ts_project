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
        if($cntPpt%2 == 0){
            $test = $this->timeConsumingCalculation(); // add parameter by yourself
        }
        echo $cntPpt." ".$test; die();        
        
//        $query = $em->getRepository('TSProjProductBundle:ProductProcessTime')->createQueryBuilder('p');
//        $query->select('p')
//              ->where('p.product = :product')->setParameter('product',$product)
//              ->groupBy('p.product')
//              ->orderBy('p.startDateTime');  
//        $productStartDate = $query->getQuery()->getResult();
        echo "ggggg"; 
//        echo date_format($productStartDate[0]->getStartDateTime(), 'Y-m-d'); die();
        echo date_format($product->getStartDateTime(),'Y-m-d');
        $proCnt = $product->getProductProcessTime();
        \Doctrine\Common\Util\Debug::dump($proCnt);
        return array();
    }
}
