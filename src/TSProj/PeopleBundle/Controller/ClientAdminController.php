<?php

namespace TSProj\PeopleBundle\Controller;
date_default_timezone_set("Asia/Bangkok");
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ClientAdminController extends CRUDController
{
    public function deleteRowAction()
    {
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $client = $em->getRepository("TSProjPeopleBundle:Client")->find($object->getId());
        $client->setDeleteFlag(1);
        $curr = new \DateTime();
        $client->setLastMaintDateTime($curr);
        $em->persist($client);
        
        $em->flush();
        
        $this->addFlash('sonata_flash_success', 'Delete successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

}
