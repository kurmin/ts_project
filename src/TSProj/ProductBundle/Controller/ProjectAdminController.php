<?php

namespace TSProj\ProductBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

}
