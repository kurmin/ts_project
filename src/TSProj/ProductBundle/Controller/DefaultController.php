<?php

namespace TSProj\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello1/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository("TSProjProductBundle:Product")->findOneByproductBarcode("TEST1234");
        
        $query = $em->getRepository('TSProjProductBundle:ProductProcessTime')->createQueryBuilder('p');
        $query->select('p')
              ->where('p.product = :product')->setParameter('product',$product)
              ->groupBy('p.product')
              ->orderBy('p.startDateTime');  
        $productStartDate = $query->getQuery()->getResult();
        echo "ggggg"; 
        echo date_format($productStartDate[0]->getStartDateTime(), 'Y-m-d'); die();
        
        return array('name' => $name);
    }
}
