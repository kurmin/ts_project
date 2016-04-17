<?php

namespace TSProj\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProjectController extends Controller
{
    /**
     * @Route("/home",name="main_ts_homepage")
     * @Template()
    
     */
    public function mainAction()
    {
        // example for retreiving data from database
        $em = $this->getDoctrine()->getEntityManager();
        
        $projects = $em->getRepository("TSProjProductBundle:Project")->findAll();
        
        //count project and divided into 4 groups
        $now   = new \DateTime();
        $curr = $now->format("Y-m-d");
        $firstDayOfMonth = date("Y-m-1", strtotime($curr));
        
        $qb = $em->createQueryBuilder();
        $qb->select('count(p.id)')
           ->from('TSProjProductBundle:Project','p')
           ->innerJoin('p.projectStatus', 'ps')      
           ->where('ps.statusName = :status')     
           ->setParameter('status', 'status_havent');
        $waitAssign = $qb->getQuery()->getSingleScalarResult(); 
        
        $qb->setParameter('status', 'status_in_progress');
        $inProgress = $qb->getQuery()->getSingleScalarResult(); 
        
        $qb->setParameter('status', 'status_hold');
        $hold = $qb->getQuery()->getSingleScalarResult(); 
        
        $qb->where('ps.statusName = :status')
           ->andWhere('p.projectEndDate >= :first')
           ->andWhere('p.projectEndDate <= :current')     
           ->setParameter('status','status_complete')
           ->setParameter('first',$firstDayOfMonth)
           ->setParameter('current',$curr);
        $finish = $qb->getQuery()->getSingleScalarResult();

//        
//        foreach($products as $product){
//            echo "Name: ".$product->getProductName()." ";
//        }
//        
//        $qb = $em->createQueryBuilder();
//        $qb->select('p')
//           ->from('TSProjProductBundle:Product','p')
//           ->innerJoin('p.project', 'pr')   
//           ->where('pr.id = :project_id')     
//           ->setParameter('project_id', 1);
//        $results = $qb->getQuery()->getResult();
//        
//        echo "</br>"."query ";
//        foreach($results as $productItem){
//            echo "Name: ".$productItem->getProductName()." ";
//        }
//        
//        // update and set detail back
//        $product_m = $em->getRepository("TSProjProductBundle:Product")->findBy(array("id"=>2,"productName"=>"fff"));
//        if(!empty($product_m)){
//            echo "</br></br>"."test update, old name is: ".$product_m[0]->getProductName()."</br>";
//            $product_m[0]->setProductName("Mint");
//            $product_m[0]->setProductDescription("mint");
//            $em->persist($product_m[0]);
//            $em->flush(); // table update here
//            echo "test update, new name is: ".$product_m[0]->getProductName()."</br>";
//            
//            //update the name back to fff
//            $product_m[0]->setProductName("fff");
//            $em->persist($product_m[0]);
//            $em->flush();
//        }
//          
        //return $this->render('TSProjProductBundle:twig:main.html.twig',array("pro1" => $products, "pro2" => $results));    
        return $this->render('TSProjProductBundle:twig:main.html.twig',
                array("project" => $projects,
                      "finish"  => $finish,
                      "hold"    => $hold,
                      "waitAssign" => $waitAssign,
                      "inProgress" => $inProgress));    
    }

    

}
