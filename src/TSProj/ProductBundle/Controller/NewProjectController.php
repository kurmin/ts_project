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
        $employee = $em->getRepository("TSProjPeopleBundle:Employee")->findOneByemployeeId($empid);
        if(count($employee)==1){
            $name = $employee->getEmployeeName()." ".$employee->getEmployeeSurname();
            $response = array("code" => 100, "success" => true,"empname"=>$name);
        }
        else
        {
         $response = array("code" => 300, "success" => true,"empname"=>"","message"=>"ไม่พบข้อมูลพนักงานที่ท่านทำการค้นหา");
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
//        $query = $em->getRepository('TSProjProductBundle:ProductProcessTime')->createQueryBuilder('p');
//        $query->select('p')
//              ->where('p.product = :product')->setParameter('product',$product)
//              ->orderBy('p.startDateTime');  
//        $productStartDateTemp = $query->getQuery()->getResult();
//        $productStartDate = date_format($productStartDateTemp[0]->getStartDateTime(), 'Y-m-d');
        
        if(count($product)==1){
            $response = array(  "code" => 100, 
                                "success" => true,
                                "productname"=>$product->getProductName(),
                                "projectid"=>$product->getProject()->getId(),
                                "projectname"=>$product->getProject()->getProjectName(),
                                "expectdate"=>$product->getProject()->getExpectedDeliveryDate(),
                                "clientname"=>$product->getProject()->getClient()->getClientName(),
                                //"productstartdate"=>date_format($product->getStartDateTime(),'Y-m-d \TH:i:s'),
                                "projectpercent"=>$product->getProject()->getPercentFinished(),
                                "itemcount"=>$product->getProject()->getAmount(),

                    );
        }
        else
        {
         $response = array("code" => 300, "success" => true,"message"=>"ไม่พบข้อมูลชิ้นงานที่ท่านกำลังค้นหา");
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
            $response = array("code" => 300, "success" => true,"message"=>"ไม่พบข้อมูลกระบวนการที่ท่านกำลังค้นหา");
        }
        return new Response(json_encode($response)); 
    }
    
    
    /**
     * @Route("/save",name="ajax_save_product_Process_Time")
     */
    public function ajax_save_product_Process_TimeAction()
    {
        
        $request = $this->container->get('request');  
        
        $productid= $request->request->get('productid'); 
        $projectid= $request->request->get('projectid');    
        $projectname= $request->request->get('projectname');    
        $customername= $request->request->get('customername');    
        $productstartdate= $request->request->get('productstartdate');    
        $deliverdate= $request->request->get('deliverdate');    
        $projectpercent= $request->request->get('projectpercent');    
        $itemcount= $request->request->get('itemcount');    
        $stock= $request->request->get('stock');    
        $empid= $request->request->get('empid');    
        $empname= $request->request->get('empname');    
        $processid= $request->request->get('processid');    
        $processname= $request->request->get('processname');    
        $pro_barcode= $request->request->get('pro_barcode');    
        $processstartdate= $request->request->get('processstartdate');    
        $processenddate= $request->request->get('processenddate');    
        
        $now   = new \DateTime();
        $curr = $now->format("Y-m-d");
        
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        
        
//        $qb->select('count(p.id)')
//           ->from('TSProjProductBundle:ProductProcessTime','ppt')
//           ->where('ppt.product = :product')     
//           ->andWhere('ppt.product = :productid')     
//           ->andWhere('ppt.process = :processid')        
//           ->andWhere('ppt.employee = :employeeid')      
//           ->andWhere('ppt.startDateTime = :startdate')   
//           ->setParameter('productid', $productid)
//           ->setParameter('processid', $processid) 
//           ->setParameter('employeeid', $empid) 
//           ->setParameter('date', $curr);
//        $findCount = $qb->getQuery()->getSingleScalarResult(); 
        
        
        $response = array("code" => 100, "success" => true,"empname"=>"Hello");
        return new Response(json_encode($response)); 
    }
    

}
