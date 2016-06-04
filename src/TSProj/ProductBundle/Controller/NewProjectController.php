<?php
namespace TSProj\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use TSProj\ProductBundle\Entity\ProductProcessTime;

date_default_timezone_set("Asia/Bangkok");
class NewProjectController extends BaseController
{
    /**
     * @Route("/new",name="new_project")
     */
    public function newAction()
    {
        //return new Response();
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository("TSProjProductBundle:Stock")->createQueryBuilder('st')
        ->where('st.stockProductQuantity > :stockqty')
        ->andwhere('st.deleteFlag <> 1')        
        ->setParameter('stockqty', '0')
        ->getQuery();
        $stock = $qb->getResult();
        
        
        return $this->render('TSProjProductBundle:twig:newproject.html.twig',array("stock"=>$stock)
        );
        
    }
    
    /**
     * @Route("/empdetail",name="ajax_get_employee_detail")
     */
    public function ajax_employee_detailAction()
    {
        $request = $this->container->get('request');       
        $emp_barcode = $request->request->get('emp_barcode');
        $em = $this->getDoctrine()->getEntityManager();
        $employee = $em->getRepository("TSProjPeopleBundle:Employee")->findOneByemployeeBarcode($emp_barcode);
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
                                "productstartdate"=>date_format($product->getStartDateTime(),'Y-m-d \TH:i:s'),
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
        $processBarcode = $request->request->get('process_barcode');
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
        $stock_id= $request->request->get('stock');    
        $emp_barcode= $request->request->get('emp_barcode');    
        $empname= $request->request->get('empname');    
        $processBarcode = $request->request->get('process_barcode');   
        $processname= $request->request->get('processname');    
        $pro_barcode= $request->request->get('pro_barcode');    
        $processstartdate= $request->request->get('processstartdate');    
        $processenddate= $request->request->get('processenddate');    
       
        $now   = new \DateTime();
        $curr = $now->format("Y-m-d");
        $day =new \DateTime($curr);
        
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $product = $em->getRepository("TSProjProductBundle:Product")->findOneByproductBarcode($productid);
        $product_id = $product->getId();
        
        $process  = $em->getRepository("TSProjProductBundle:Process")->findOneByprocessBarcode($processBarcode);
        if(count($process)==1){
            $process_id =  $process->getId();
        }
        
        $employee = $em->getRepository("TSProjPeopleBundle:Employee")->findOneByemployeeBarcode($emp_barcode);
        if(count($employee)==1){
            $emp_id = $employee->getId();
        }
        
        $stock = $em->getRepository("TSProjProductBundle:Stock")->findOneById($stock_id);
        if($stock){
            $amt = $stock->getStockProductQuantity() - 1;
            $stock->setStockProductQuantity($amt);
            $product->setStock($stock);
            $em->persist($stock);
            $em->persist($product);
            $em-flush();
        }else {   
            // in case scan product to calculate time consuming (not from the stock)
            $qb->select('ppt')
               ->from('TSProjProductBundle:ProductProcessTime','ppt')
               ->innerJoin('ppt.product', 'pd')      
               ->innerJoin('ppt.process', 'pc')      
               ->innerJoin('ppt.employee', 'emp')      
               ->Where('ppt.startDateTime = :startdate') 
               ->andwhere('pd.id = :productid')     
               ->andWhere('pc.id = :process_id')        
               ->andWhere('emp.id = :employeeid')      
               ->andWhere('ppt.endDateTime = :null')   
               ->setParameter('productid', $product_id)
               ->setParameter('process_id', $process_id) 
               ->setParameter('employeeid', $emp_id) 
               ->setParameter('endDate', null);
            $ProductProcessTime = $qb->getQuery()->getResult();

            if(!$ProductProcessTime)
            {
                //insert new record
                $newProductProcessTime = new ProductProcessTime();
                $newProductProcessTime->setProduct($product);
                $newProductProcessTime->setProcess($process);
                $newProductProcessTime->setEmployee($employee);
                $newProductProcessTime->setStartDateTime($day);
                $newProductProcessTime->setTimeConsuming('0');
                $newProductProcessTime->setFinishedFlag('0');
                $newProductProcessTime->setLastMaintDateTime($now);
                $newProductProcessTime->setApprovalEmployee(null);

                $product->setCurrentPhase($process);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newProductProcessTime);
                $entityManager->persist($product);
                $entityManager->flush();
                $code = 100;
            }
            else
            {    
                if(count($ProductProcessTime == 1)){
                    $ProductProcessTime->setEndDateTime($now);
                    //calculate time consuming her and do not forget to deduct break time at noon
                    $em->persist($ProductProcessTime);
                    $em->flush();
                    $code = 100;
                }else{
                    $code = 300; 
                    $message = "การแสกนครั้งนี้ไม่ถูกต้อง กรุณาตรวจสอบ ProductProcessTime Admin อีกครั้ง";
                }
            }
        }
        
          $response = array("code" => $code, 
                  "success" => true,
                  "message" => $message,
                  "empname"=>"Hello",
                  "product_id"=>$product_id,
                  "process_id"=>$process_id,
                  "now"=>$now,
                  "curr"=>$curr,
                  "ProductProcessTimeId"=>$ProductProcessTimeId,
                  "emp_id"=>$emp_id);
          return new Response(json_encode($response)); 
    }
    

}
