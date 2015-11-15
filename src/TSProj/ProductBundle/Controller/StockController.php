<?php

namespace TSProj\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StockController
 *
 * @author rittichai.hiranlikit
 */
class StockController extends Controller{
    /**
     * @Route("/stockcreate",name="stock_create")
     * @Template()
     */
    public function stockcreateAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $Stock = $em->getRepository("TSProjProductBundle:Stock")->findAll();
                 
        return $this->render('TSProjProductBundle:twig:stock.html.twig',array("stock" => $Stock));    
    }
}
