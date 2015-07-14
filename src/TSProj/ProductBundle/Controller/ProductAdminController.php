<?php

namespace TSProj\ProductBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProductAdminController extends CRUDController
{
    public function deleteRowAction()
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $product = $em->getRepository("TSProjProductBundle:Product")->find($object->getId());
        $product->setDeleteFlag(1);
        $curr = new \DateTime();
        $product->setLastMaintDateTime($curr);
        $product->setPrevProject($product->getProject()->getId());
        $product->setProject(null);
        $em->persist($product);
        $em->flush();
        
        $this->addFlash('sonata_flash_success', 'Delete successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

}
