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

        $pdf->SetFont('helvetica', '', 11, '', true);
        $pdf->AddPage();
        
        //---------------- generate barcode --------------------------
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => true,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );

        // PRINT VARIOUS 1D BARCODES

        // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
//        $pdf->Cell(0, 0, 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9', 0, 1);
//        $pdf->write1DBarcode('CODE 39', 'C39', '', '', '', 18, 0.4, $style, 'N');
//        

        //$html = '<h1>Working on Symfony</h1>';
        
        $html = $this-> RenderView('TSProjProductBundle:Export:projectPdf.html.twig',  
                array( 'lead' => 'one' , 
                        'project' => $project, 
                        'heureConclusion' => '$heureConclusion' , 
                        'adresseIP' => '$adresseIP'
                    ));
  
        $pdf -> WriteHTML ($html, true, false, true, false, '');
        //$pdf -> Output ( $pdfPath . '/pnv.pdf' , 'F' );
        $pdf->Output("example.pdf", 'I');
    }

}
