<?php

namespace TSProj\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class NewProjectController extends Controller
{
    /**
     * @Route("/new",name="new_project")
     */
    public function newAction()
    {
        //return new Response();
        return $this->render('TSProjProductBundle:twig:newproject.html.twig',array()
        );
        
    }
    
    /**
     * @Route("/empdetail",name="ajax_get_employee_detail")
     */
    public function ajax_employee_detailAction()
    {
        $request = $this->container->get('request');       
        $empid = $request->request->get('empid');
        $em = $this->getDoctrine()->getEntityManager();
        $employee = $em->getRepository("TSProjPeopleBundle:Employee")->find($empid);
        if(count($employee)==1){
            $response = array("code" => 100, "success" => true,"name"=>$employee->getEmployeeName(),"surname"=>$employee->getEmployeeSurname());
        }
        else
        {
         $response = array("code" => 300, "success" => true,"name"=>"","surname"=>"","message"=>"no data found");
        }
        return new Response(json_encode($response)); 
    }
    
    /**
     * @Route("/productdetail",name="ajax_get_product_detail")
     */
    public function ajax_product_detailAction()
    {
        $request = $this->container->get('request');       
        $productBarcode = $request->request->get('productBarcode');
        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository("TSProjProductBundle:Product")->findOneByproductBarcode($productBarcode);
        if(count($product)==1){
            $response = array(  "code" => 100, 
                                "success" => true,
                                "productname"=>$product->getProductName(),
                                "projectid"=>$product->getProject()->getId(),
                                "projectname"=>$product->getProject()->getProjectName(),
                                "expectdate"=>$product->getProject()->getExpectedDeliveryDate(),
                                "clientname"=>$product->getProject()->getClient()->getClientName()
                    );
        }
        else
        {
         $response = array("code" => 300, "success" => true,"message"=>"no data found");
        }
        return new Response(json_encode($response)); 
    }
    
    /**
     * @Route("/processdetail",name="ajax_get_process_detail")
     */
    public function ajax_process_detailAction()
    {
        $request = $this->container->get('request');       
        $processBarcode = $request->request->get('processid');
        $em = $this->getDoctrine()->getEntityManager();
        $process = $em->getRepository("TSProjProductBundle:Process")->findOneByprocessBarcode($processBarcode);
        if(count($process)==1){
            $response = array("code" => 100, "success" => true,"name"=>$process->getProcessName());
        }
        else
        {
            $response = array("code" => 300, "success" => true,"message"=>"no data found");
        }
        return new Response(json_encode($response)); 
    }

}
