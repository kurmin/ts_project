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
     * @Route("/stock",name="stocklist")
     */
        public function stockAction()
    {
        //return new Response();
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository("TSProjProductBundle:Stock")->createQueryBuilder('st')
        ->where('st.stockProductQuantity > :stockqty')
        ->andwhere('st.deleteFlag <> 1')        
        ->setParameter('stockqty', '0')
        ->getQuery();
        $stock = $qb->getResult();
        
        $qb = $em->createQueryBuilder();
        $qb->select('count(p.id)')
           ->from('TSProjProductBundle:Stock','p')   
           ->where('p.deleteFlag <> 1');
        
        $stockcount = $qb->getQuery()->getSingleScalarResult();
        
        return $this->render('TSProjProductBundle:twig:stocklist.html.twig',
                array("stock"=>$stock,
                      "stockcount"  => $stockcount
                     ));    
        
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
            if($product->getProductTimeConsumingDays()== 0 &&
               $product->getProductTimeConsumingHours() == 0 &&
               $product->getProductTimeConsumingMins() == 0){
                //disable = false
                $stockflag = false;
            }else{
                $stockflag = true;
            }
            if($product->getStock()){
                $response = array("code" => 300, "success" => true,"message"=>"ชิ้นงานนี้ผูกกับสต๊อก ".$product->getStock()->getStockProductName()." แล้ว หากต้องการยกเลิกโปรดติดต่อ admin");
            }else{
                if($product->getStartDateTime()==null){
                    $startDate = new \DateTime();
                }else{
                    $startDate = $product->getStartDateTime();
                }
                $response = array(  "code" => 100, 
                                    "success" => true,
                                    "productname"=>$product->getProductName(),
                                    "projectid"=>$product->getProject()->getId(),
                                    "projectname"=>$product->getProject()->getProjectName(),
                                    "expectdate"=>$product->getProject()->getExpectedDeliveryDate(),
                                    "clientname"=>$product->getProject()->getClient()->getClientName(),
                                    "productstartdate"=>date_format($startDate,'Y-m-d H:i:s'),
                                    "projectpercent"=>$product->getProject()->getPercentFinished(),
                                    "itemcount"=>$product->getProject()->getAmount(),
                                    "stockflag"=>$stockflag,
                                    "productstatus"=>$product->getProductStatus()->getStatusDescription(),

                        );
            }   
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
        $productBarcode = $request->request->get('productBarcode');
        $em = $this->getDoctrine()->getEntityManager();
        $process = $em->getRepository("TSProjProductBundle:Process")->findOneByprocessBarcode($processBarcode);
        $product = $em->getRepository("TSProjProductBundle:Product")->findOneByproductBarcode($productBarcode);
        
        if(count($process)==1){
            if($product->getProcess()->contains($process)){       
                $response = array("code" => 100, "success" => true,
                                  "name"=>$process->getProcessName()
//                             ,"message"=>$productid
                                 );
                              //"processtime"=>$lastppt->getTimeConsuming());
            }else{
                $response = array("code" => 300, "success" => true,"message"=>"ข้อมูลกระบวนการที่ท่านกำลังค้นหาไม่ตรงกับรหัสสินค้า");
            }              
        }else
        {
            $response = array("code" => 300, "success" => true,"message"=>"ไม่พบข้อมูลกระบวนการที่ท่านกำลังค้นหา");
        }
        return new Response(json_encode($response)); 
    }
    
    
    /**
     * @Route("/productprocesstime",name="ajax_get_productprocess_time")
     */
    public function ajax_product_process_timeAction()
    {
        $request = $this->container->get('request');       
        $productBarcode = $request->request->get('productBarcode');
        $emp_barcode = $request->request->get('emp_barcode');
        $processBarcode = $request->request->get('processBarcode');
        
        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository("TSProjProductBundle:Product")->findOneByproductBarcode($productBarcode);
        
        if(count($product)==1){
            $product_id= $product->getId();
        }
        
        $process  = $em->getRepository("TSProjProductBundle:Process")->findOneByprocessBarcode($processBarcode);
        if(count($process)==1){
            $process_id =  $process->getId();
        }
        
        $employee = $em->getRepository("TSProjPeopleBundle:Employee")->findOneByemployeeBarcode($emp_barcode);
        if(count($employee)==1){
            $emp_id = $employee->getId();
        }
            
        // get start process date
        list($date,$timeConsum) = $this->getStartProcessDate($product,$process,$employee);
        $startProcessDate = $date->format('Y-m-d H:i:s');
                
        $query = $em->createQuery('SELECT ppt.id, COALESCE(min(ppt.startDateTime),CURRENT_TIMESTAMP()) as startDateTime, COALESCE(min(ppt.startDateTime),0) as NULLCHECK  from TSProjProductBundle:ProductProcessTime ppt where ppt.product = :productid and ppt.process = :process_id and ppt.employee = :employeeid and ppt.endDateTime is NULL ');
        $query->setParameter('productid',$product_id);
        $query->setParameter('process_id', $process_id);
        $query->setParameter('employeeid', $emp_id);
        $ProductProcessTime = $query->getResult();

        if($ProductProcessTime[0]['id']  != null){
            $ppt = $ProductProcessTime[0];
            $now   = new \DateTime();
            $curr = $now->format("Y-m-d");
            $ppt_id = $ppt['id'];
            $ppt2 = $em->getRepository("TSProjProductBundle:ProductProcessTime")->find($ppt_id);
            $start = $ppt2->getStartDateTime();

            if($start->format("Y-m-d") == $curr){
                //calculate Time consuming
                $diff = $now->diff($start);
                 $timeConsum += $diff->format('%h')*60+$diff->format('%i');
                //break time
                if($start->format('H') < 12 && $now->format('H') > 12){
                    $timeConsum = $timeConsum - 60;
                }
            }
            
        }    
                
        if($product->getProcess()->contains($process)){
            if(count($ProductProcessTime)==1){  
                //case end_process_date is null
                $response = array("code" => 100, "success" => true,
                                  "result"=>$ProductProcessTime,
                                  "timeconsum"=>$timeConsum,
                                  "startprocessdate"=>$startProcessDate,
                                  "message"=>"success"
                                     );
            }
            else
            {
                $response = array("code" => 300, "success" => true,
                                  "timeconsum"=>$timeConsum,
                                  "startprocessdate"=>$startProcessDate,
                                  "message"=>"ไม่พบข้อมูลเวลาเริ่มต้นกระบวนการ");
            }
        }else{
            $response = array("code" => 300, "success" => true,
                              "message"=>"ข้อมูลกระบวนการที่ท่านกำลังค้นหาไม่ตรงกับรหัสสินค้า");
        }    
        return new Response(json_encode($response)); 
    }
    
    /**
     * @Route("/save",name="ajax_save_product_Process_Time")
     */
    public function ajax_save_product_Process_TimeAction()
    {
        
        $request = $this->container->get('request');  
        $message = "";
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
        $finishprocess= $request->request->get('finishprocess_value');
        $processtime= $request->request->get('processtime');

        $now   = new \DateTime();
        $curr = $now->format("Y-m-d");
        $timeConsum = 0;
        
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
            
            // set product status to finish
            $finishStatus = $em->getRepository('TSProjProductBundle:WorkStatus')->find(4);
            $product->setProductStatus($finishStatus);
            $product->setPercentFinished(100);
            $code = 100;
            
            $em->persist($stock);
            $em->persist($product);
            $em->flush();
        }else {   
            // in case scan product to calculate time consuming (not from the stock)
            $qb->select('ppt')
               ->from('TSProjProductBundle:ProductProcessTime','ppt')
               ->innerJoin('ppt.product', 'pd')      
               ->innerJoin('ppt.process', 'pc')      
               ->innerJoin('ppt.employee', 'emp')      
               ->where('pd.id = :productid')     
               ->andWhere('pc.id = :process_id')        
               ->andWhere('emp.id = :employeeid')      
               ->andWhere('ppt.endDateTime is NULL')      
               ->setParameter('productid', $product_id)
               ->setParameter('process_id', $process_id) 
               ->setParameter('employeeid', $emp_id);
            $ProductProcessTime = $qb->getQuery()->getResult();

            if($ProductProcessTime==null)
            { 
                //insert new record
                $newProductProcessTime = new ProductProcessTime();
                $newProductProcessTime->setProduct($product);
                $newProductProcessTime->setProcess($process);
                $newProductProcessTime->setEmployee($employee);
                $newProductProcessTime->setStartDateTime($now);
                $newProductProcessTime->setTimeConsuming('0');
                $newProductProcessTime->setFinishedFlag('0');
                $newProductProcessTime->setLastMaintDateTime($now);
                $newProductProcessTime->setApprovalEmployee(null);

                $product->setCurrentPhase($process);
                $onProcessStatus = $em->getRepository('TSProjProductBundle:WorkStatus')->find(3);
                $product->setProductStatus($onProcessStatus);
                
                $project = $product->getProject();
                $project->setProjectStartDate($now);
                        
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newProductProcessTime);
                $entityManager->persist($product);
                $entityManager->persist($project);
                $entityManager->flush();
                $code = 100;
            }
            else
            {   $cnt=count($ProductProcessTime);
                if($cnt == 1)
                    {
                    $ProductProcessTime = $ProductProcessTime[0];
                    $start = $ProductProcessTime->getStartDateTime();
                    $processDate = $start->format("Y-m-d");
                    if($processDate == $curr){
                        $ProductProcessTime->setEndDateTime($now);
                        $ProductProcessTime->setLastMaintDateTime($now);
                        //calculate Time consuming
                        $diff = $now->diff($start);
                        $timeConsum += $diff->format('%h')*60+$diff->format('%i');
                        //break time
                        if($start->format('H') < 12 && $now->format('H') > 12){
                            $timeConsum = $timeConsum - 60;
                        }
                        $ProductProcessTime->setTimeConsuming($timeConsum);
                        if($finishprocess=="Y"){
                            $ProductProcessTime->setFinishedFlag(true);

                            //update all productProcessTime
                            $this->updateProductProcessTimeStatus($product,$process);
                            $this->updateProductStatus($product);
                            $this->updateProductProject($product_id);
                        }
                        
                        
                        $em->persist($ProductProcessTime);
                        $em->flush();
                        $code = 100;
                    }else{
                        $code = 300;
                        $message = "การสแกนครั้งนี้ไม่ถูกต้อง - สแกนข้ามวัน";
                    }
                }else{
                    $code = 300; 
                    $message = "การสแกนครั้งนี้ไม่ถูกต้อง กรุณาติดต่อ Admin";
                }
            }
        }
        
        
        
        $response = array("code" => $code, 
                "success" => true,
                "message" => $message
//                  "empname"=>"Hello",
//                  "product_id"=>$product_id,
//                  "process_id"=>$process_id,
//                  "now"=>$now,
//                  "curr"=>$curr,
//                  "ProductProcessTimeId"=>$ProductProcessTime->getId(),
//                  "emp_id"=>$emp_id
                );
        return new Response(json_encode($response)); 
    }
    
    public function updateProductProject($product_id){

        $em = $this->getDoctrine()->getEntityManager();
        //count number of product process time
        $qb = $em->createQueryBuilder();
        $qb->select('p.id')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->where('p.product = :productId')
           ->setParameter('productId',$product_id);
        $ppt = $qb->getQuery()->getResult(); 
        $cntPpt = count($ppt);

        $product = $em->getRepository("TSProjProductBundle:Product")->find($product_id);
        $ppts = $product->getProductProcessTime();
        if($cntPpt > 0){
            
        $TimeConsuming = 0;
        
            foreach($ppts as $item){
                $TimeConsumingA = $item->getTimeConsuming();
                $TimeConsuming = $TimeConsuming + $TimeConsumingA;

            }

        $result = $this->timeConsumingCalculation($TimeConsuming);
        $product->setProductTimeConsumingDays($result[0]);
        $product->setProductTimeConsumingHours($result[1]);
        $product->setProductTimeConsumingMins($result[2]);
        
        $esDay = $product->getEstimatedTimeDay();
        $esHour = $product->getEstimatedTimeHour();
        $esMin = $product->getEstimatedTimeMin();
        
        //count distinct number of processes that have been finished
        
        $qb2 = $em->createQueryBuilder();
        $qb2->select('count( distinct p.process)')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->innerJoin('p.product', 'pd')      
           ->where('pd.id = :productId') 
           ->andWhere('p.finishedFlag = 1')     
           ->setParameter('productId',$product_id);
        $finishedCount = $qb2->getQuery()->getSingleScalarResult(); 
        
        //check whether all processes have been finished or not
        
        $noOfProcess =  $product->getNoOfProcess();
        $ProductStatus = $product->getProductStatus();
        
        
        $finished = 0;
        
        if ($ProductStatus == 'เสร็จสิ้น')
        {
            $finished = 1;
        }     
        elseif($finishedCount ==  $noOfProcess)
        {
            $finished = 1;
            
//            //update product status when all processes are finished >> change to paragraph updateProductStatus
//            $finishStatus = $em->getRepository('TSProjProductBundle:WorkStatus')->find(4);
//            $product->setProductStatus($finishStatus);
        }
         
        $resultPercent = $this->percentFinishedCalculation($TimeConsuming, $esDay, $esHour, $esMin, $finished);
        $product-> setPercentFinished($resultPercent);
        

        //update product start date and end date time
       
        $qb3 = $em->createQueryBuilder();
        $qb3->select('p')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->innerJoin('p.product', 'pd')      
           ->where('pd.id = :productId')     
           ->setParameter('productId',$product_id)
           ->orderBy('p.startDateTime');  
        $products = $qb3->getQuery()->getResult();
        $product->setStartDateTime($products[0]->getStartDateTime());
        
        if($finished == 1)
        {
            $qb4 = $em->createQueryBuilder();
            $qb4->select('p')
               ->from('TSProjProductBundle:ProductProcessTime','p')
               ->innerJoin('p.product', 'pd')      
               ->where('pd.id = :productId')     
               ->setParameter('productId', $product_id)
               ->orderBy('p.endDateTime', 'desc');  
            $products = $qb4->getQuery()->getResult();
            $product->setEndDateTime($products[0]->getEndDateTime());
        }
        //Sum project time consuming       
        
        $qb5 = $em->createQueryBuilder();
        $qb5->select('pj.id')
           ->from('TSProjProductBundle:Product','pd')
           ->innerJoin('pd.project', 'pj')   
           ->where('pd.id = :productId')     
           ->setParameter('productId', $product_id);
        $projectId = $qb5->getQuery()->getSingleScalarResult(); 
        
        $project = $em->getRepository("TSProjProductBundle:Project")->find($projectId);//insert id product into ()
        
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
                
                $projEsDay += $item2->getEstimatedTimeDay();
                $projEsHour +=  $item2->getEstimatedTimeHour();
                $projEsMin +=  $item2->getEstimatedTimeMin();
                
                $projTimeConsuming = $projTimeConsuming + ($Consume_Day * 24 * 60) + ($Consume_Hour * 60) + $Consume_Min;
        }

        $resultProj = $this->timeConsumingCalculation($projTimeConsuming);
        $project->setTimeConsumingDays($resultProj[0]);
        $project->setTimeConsumingHours($resultProj[1]);
        $project->setTimeConsumingMins($resultProj[2]);
        
        //Count number of products in this project
        
        $qb6 = $em->createQueryBuilder();
        $qb6->select('count(distinct pd.id)')
           ->from('TSProjProductBundle:Product','pd')
           ->innerJoin('pd.project', 'pj')   
           ->where('pj.id = :projectId')     
           ->setParameter('projectId', $projectId);
        $noOfProducts = $qb6->getQuery()->getSingleScalarResult(); 

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
        
        $projectFinished = 0;
        $ProjectStatus = $project->getProjectStatus();
      
        
        if ($ProjectStatus == 'เสร็จสิ้น')
        {
            $projectFinished = 1;
        }     
        else
        if ($noOfFinishedProducts ==  $noOfProducts)
        {
            $projectFinished = 1;
            $finishStatus = $em->getRepository('TSProjProductBundle:WorkStatus')->find(4);
            $project->setProjectStatus($finishStatus);
        }
                 
        $projPercent = $this->percentFinishedCalculation($projTimeConsuming, $projEsDay, $projEsHour, $projEsMin, $projectFinished);
        $project-> setPercentFinished($projPercent);
        
        $em->persist($project); 
        $em->persist($product);
        $em->flush();
        }
    }

    public function updateProductProcessTimeStatus($product,$process){
        $em = $this->getDoctrine()->getEntityManager();
        $now = new \DateTime();
        //product_process_timeProductProcessTime
        $qb = $em->createQueryBuilder();
        $qb->select('p')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->innerJoin('p.product','pd')
           ->innerJoin('p.process','pc')     
           ->where('pd.id = :productId')  
           ->andWhere('pc.id = :processId')     
           ->setParameter('productId',$product->getId())
           ->setParameter('processId',$process->getId());          
        $ppts = $qb->getQuery()->getResult();
                
        foreach($ppts as $ppt){
            $ppt->setFinishedFlag(true);
            $ppt->setLastMaintDateTime($now);
            $em->persist($ppt);
        }         
        $em->flush();
    }
    
    public function updateProductStatus($product){
        $em = $this->getDoctrine()->getEntityManager();
//        $qb = $em->createQuery('SELECT pp FROM TSProjProductBundle:ProductProcess pp WHERE pp.product_id = :productId ');
        $respository = $em->getRepository('TSProjProductBundle:Process');
        $query = $respository->createQueryBuilder('p')
                ->innerJoin('p.product','pd')
                ->where('pd.id = :productId')
                ->setParameter('productId',$product->getId());
        $pps = $query->getQuery()->getResult();
                
        $repo = $em->getRepository('TSProjProductBundle:ProductProcessTime');
        $query2 = $repo->createQueryBuilder('ppt')
                ->select('ppt')
                ->where('ppt.product = :productId')
                ->andWhere('ppt.deleteFlag = :deleteFlag') 
                ->groupBy('ppt.product','ppt.process')
                ->setParameter('productId',$product->getId())
                ->setParameter('deleteFlag',0);
        $ppts = $query2->getQuery()->getResult();
     
        $now = new \DateTime();                 
        
        if(count($pps) == count($ppts)){
            $query3 = $repo->createQueryBuilder('ppt')
                    ->select('ppt')
                    ->where('ppt.product = :productId')
                    ->andWhere('ppt.deleteFlag = :deleteFlag') 
                    ->andWhere('ppt.finishedFlag = :finishedFlag')
                    ->setParameter('productId',$product->getId())
                    ->setParameter('deleteFlag',0)
                    ->setParameter('finishedFlag',0);
            $ppt_no = $query3->getQuery()->getResult();
            if(count($ppt_no)==0){
                $finishStatus = $em->getRepository('TSProjProductBundle:WorkStatus')->find(4);
                $product->setProductStatus($finishStatus);
                $product->setLastMaintDateTime($now);
                $em->persist($product); 
            }    
        }      
        $em->flush();
    }


    public function getStartProcessDate($product,$process,$employee){
        $em = $this->getDoctrine()->getEntityManager();
        //product_process_time
        $qb = $em->createQueryBuilder();
        $qb->select('p')
           ->from('TSProjProductBundle:ProductProcessTime','p')
           ->innerJoin('p.product','pd')
           ->innerJoin('p.process','pc')     
           ->innerJoin('p.employee','emp')     
           ->where('pd.id = :productId')  
           ->andWhere('pc.id = :processId')  
           ->andWhere('emp.id = :empId')     
           ->orderBy('p.startDateTime','ASC')     
           ->setParameter('productId',$product->getId())
           ->setParameter('processId',$process->getId())
           ->setParameter('empId', $employee->getId());          
        $ppt = $qb->getQuery()->getResult();
        $timeconsum = 0;
        $date = new \DateTime();
        if(count($ppt)>=1){
            $date = $ppt[0]->getStartDateTime();
            foreach($ppt as $p){
                $timeconsum += $p->getTimeConsuming();
            }
        }    
        return array($date,$timeconsum);
    }
}
