<?php

namespace TSProj\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProjectController extends Controller
{
    /**
     * @Route("/main",name="main_ts_homepage")
     * @Template()
    
     */
    public function mainAction()
    {
        // example for retreiving data from database
        $em = $this->getDoctrine()->getEntityManager();
        
        $products = $em->getRepository("TSProjProductBundle:Product")->findAll();
        foreach($products as $product){
            echo "Name: ".$product->getProductName()." ";
        }
        
        $qb = $em->createQueryBuilder();
        $qb->select('p')
           ->from('TSProjProductBundle:Product','p')
           ->innerJoin('p.project', 'pr')   
           ->where('pr.id = :project_id')     
           ->setParameter('project_id', 1);
        $results = $qb->getQuery()->getResult();
        
        echo "</br>"."query ";
        foreach($results as $productItem){
            echo "Name: ".$productItem->getProductName()." ";
        }
        
        // update and set detail back
        $product_m = $em->getRepository("TSProjProductBundle:Product")->findBy(array("id"=>2,"productName"=>"fff"));
        if(!empty($product_m)){
            echo "</br></br>"."test update, old name is: ".$product_m[0]->getProductName()."</br>";
            $product_m[0]->setProductName("Mint");
            $product_m[0]->setProductDescription("mint");
            $em->persist($product_m[0]);
            $em->flush(); // table update here
            echo "test update, new name is: ".$product_m[0]->getProductName()."</br>";
            
            //update the name back to fff
            $product_m[0]->setProductName("fff");
            $em->persist($product_m[0]);
            $em->flush();
        }
          
        return $this->render('TSProjProductBundle:twig:main.html.twig',array("pro1" => $products, "pro2" => $results));    
    }

    

}
