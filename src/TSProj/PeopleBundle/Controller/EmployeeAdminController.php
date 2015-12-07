<?php

namespace TSProj\PeopleBundle\Controller;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EmployeeAdminController extends CRUDController
{
    public function deleteRowAction()
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $emp = $em->getRepository("TSProjPeopleBundle:Employee")->find($object->getId());
        $emp->setDeleteFlag(1);
        $curr = new \DateTime();
        $emp->setLastMaintDateTime($curr);
        $em->persist($emp);
        
        $em->flush();
        
        $this->addFlash('sonata_flash_success', 'Delete successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
