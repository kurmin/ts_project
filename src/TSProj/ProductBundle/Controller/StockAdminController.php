<?php

namespace TSProj\ProductBundle\Controller;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StockAdminController extends CRUDController
{
    public function deleteRowAction()
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $stock = $em->getRepository("TSProjProductBundle:Stock")->find($object->getId());
        $stock->setDeleteFlag(1);
        $curr = new \DateTime();
        $stock->setLastMaintDateTime($curr);
        $em->persist($stock);
        $em->flush();
        
        $this->addFlash('sonata_flash_success', 'Delete successfully');
        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
