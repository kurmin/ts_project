<?php

namespace TSProj\ProductBundle\Controller;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectAdminController extends CRUDController
{
//    public function render($view, array $parameters = array(), Response $response = null)
//    {
//	// Get categories from parameters
//	$parameters['categories'] = $this->container->getParameter("Project.projectStatus");
// 
//	// This one is also necessary. I'll explain in the next section ;)
//	$parameters['persistent_parameters'] = $this->admin->getPersistentParameters();
// 
//	return parent::render($view, $parameters);
//    }
    public function projectDeleteRowAction()
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $project = $em->getRepository("TSProjProductBundle:Project")->find($object->getId());
        $project->setDeleteFlag(1);
        $curr = new \DateTime();
        $project->setLastMaintDateTime($curr);
        
        //delete product
        $products = $em->getRepository("TSProjProductBundle:Product")->findBy(array('project'=>$project->getId()));
        foreach ($products as $product){
            $product->setDeleteFlag(1);
            $product->setLastMaintDateTime($curr);
            $product->setProject(null);
            $product->setPrevProject($project->getId());
            $em->persist($product);
        }
        
        $em->persist($project);
        $em->flush();
        
        $this->addFlash('sonata_flash_success', 'Delete successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
    
    public function projectPdfAction(Request $request){

        //---------------- get value ---------------------------------
        $object = $this->admin->getSubject();
        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }
        $em = $this->getDoctrine()->getEntityManager();
        $project = $em->getRepository("TSProjProductBundle:Project")->find($object->getId());
        
        $pdf = $this->container->get("white_october.tcpdf")->create(
            'PORTRAIT',
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );
        $pdf->SetAuthor('qweqwe');
        $pdf->SetTitle('Prueba TCPDF');
        $pdf->SetSubject('Your client');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $pdf->setFontSubsetting(true);
        // Remove default header / footer
        $pdf -> setPrintHeader (false);
        $pdf -> setPrintFooter (false);
        // Set default are monospaced
        $pdf -> SetDefaultMonospacedFont (PDF_FONT_MONOSPACED);
        // Set margins
        $pdf -> SetMargins (PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // Set auto page breaks
        $pdf -> SetAutoPageBreak (TRUE, PDF_MARGIN_BOTTOM);

        
        
        $pdf->SetFont('freeserif', '', 12, '', true);
        $pdf->AddPage();
        
        //---------------- generate barcode --------------------------
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 0
        );

        // PRINT VARIOUS 1D BARCODES

        // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
        //$pdf->Cell(0, 0, 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9', 0, 1);
        //$pdf->write1DBarcode($project->getProjectBarcode(), 'C128', '', '', '', 18, 0.4, $style, 'M');
        //$pdf->write1DBarcode($project->getProjectBarcode(), 'C128B', 10, 50, 105, 18, 0.4, $style, 'N');
//        

        //$htmla = '<h1>Working on Symfony</h1>';
        $params = $pdf->serializeTCPDFtagParameters(array($project->getProjectBarcode(), 'C128', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
        //$test = $pdf->write1DBarcode($project->getProjectBarcode(), 'C128', '50', '50', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N');
       
        $productTable = '<table border="1"><tr align="Center"><td width="40%">รายการ</td><td width="15%">รหัสแบบ</td><td width="15%">วัสดุ</td><td width="15%">จำนวน</td><td width="15%">หมายเหตุ</td></tr>';        
        $product = $project->getProduct();
        
        /*foreach($product as $item){
           $item->getProductDescription();
           $productTable .= '<tr><td>' . $item . '</td>'
            //$productTable .= '<tr><td></td>'
                    . '<td></td>'
                    . '<td></td>'
                    . '<td></td>'
                    . '<td></td>';
            $productTable .= '</tr>';
        }
        $productTable .= '</table>';*/

        $n = 0;
        while ($n <= 20) {
            if($n < count($product)){
            
            $productTable .= '<tr><td>' . $product[$n]->getProductDescription() . '</td>'
                    . '<td align ="center">'. $product[$n]->getDrawingID() . '</td>'
                    . '<td align ="center">'. $product[$n]->getMaterial() . '</td>'
                    . '<td align ="center">'. $project->getAmount() . ' UNIT</td>'
                    . '<td></td>';
            $productTable .= '</tr>';
            }
            else
            {
                $productTable .= '<tr><td></td>'
                    . '<td></td>'
                    . '<td></td>'
                    . '<td></td>'
                    . '<td></td>';
                $productTable .= '</tr>';
            }
            $n++;
        }
        $productTable .= '</table>';
        
        $html = $this-> RenderView('TSProjProductBundle:Export:projectPdf.html.twig',  
                array( 'lead' => 'one' , 
                        'project' => $project, 
                        'product' => $project->getProduct(), 
                        'orderDate' => $project->getorderDate()->format('d/m/Y'),
                        'expectedDeliveryDate' => $project->getexpectedDeliveryDate()->format('d/m/Y'),
                        'params' => $params,
                     //'test' => $test,
                    ));
        
        $ending = '<br/><br/><table><tr><td width="50%" ></td><td width="50%" align="center">ลงชื่อ ........................................ ผู้จัดทำ</td></tr>'
                    . '<tr><td width="50%" ></td><td width="50%" align="center">......./......./.......</td></tr>'
                    . '<tr><td width="50%"></td><td width="50%"></td></tr>'
                    . '<tr><td width="50%" ></td><td width="50%" align="center">ลงชื่อ ........................................ ผู้อนุมัติ</td></tr>'
                    . '<tr><td width="50%" ></td><td width="50%" align="center">......./......./.......</td></tr></table>';
  
        $pdf -> WriteHTML ($html, true, false, true, false, '');
        $pdf -> writeHTML ($productTable, true, false, true, false, '');
        $pdf -> writeHTML ($ending, true, false, true, false, '');
        //$pdf -> Output ( $pdfPath . '/pnv.pdf' , 'F' );
        $pdf->Output("example.pdf", 'I');
    }

}
