<?php

namespace TSProj\ProductBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProcessAdminController extends CRUDController
{
    public function deleteRowAction()
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $process = $em->getRepository("TSProjProductBundle:Process")->find($object->getId());
        $process->setDeleteFlag(1);
        $curr = new \DateTime();
        $process->setLastMaintDateTime($curr);
        $em->persist($process);
        $em->flush();
        
        $this->addFlash('sonata_flash_success', 'Delete successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

}
