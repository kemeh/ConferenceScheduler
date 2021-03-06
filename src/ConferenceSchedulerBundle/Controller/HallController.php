<?php

namespace ConferenceSchedulerBundle\Controller;

use ConferenceSchedulerBundle\Entity\Hall;
use ConferenceSchedulerBundle\Entity\Venue;
use ConferenceSchedulerBundle\Form\HallType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HallController
 *
 *  @Route("hall")
 * @Security("has_role('ROLE_USER')")
 */
class HallController extends Controller
{
    /**
     * @Route("/create/{venue_id}", name="create_hall", methods={"POST", "GET"})
     * @param Request $request
     * @return Response
     * @Security("has_role('ROLE_SITE_ADMIN')")
     */
    public function createAction(Request $request, int $venue_id)
    {
        $hall = new Hall();
        $form = $this->createForm(HallType::class, $hall);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $venue = $this->getDoctrine()->getRepository(Venue::class)->find($venue_id);
            $hall->setVenue($venue);

            $em = $this->getDoctrine()->getManager();
            $em->persist($hall);
            $em->flush();

            $this->addFlash("success", "You have created a new Hall!");
            return $this->redirectToRoute('details_venue', array('id' => $hall->getVenue()->getId()));
        }

        return $this->render('hall/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/{id}", name="details_hall")
     * @Method("GET")
     */
    public function detailsAction(Hall $hall)
    {
        return $this->render('hall/details.html.twig', array('hall' => $hall, ));
    }

    /**
     * @Route("/{id}/edit", name="edit_hall")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_SITE_ADMIN')")
     */
    public function editAction(Request $request, Hall $hall)
    {
        $editForm = $this->createForm(HallType::class, $hall);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash("success", "You have edited " . $hall->getName());
            return $this->redirectToRoute('details_hall', array('id' => $hall->getId(),));
        }

        return $this->render('hall/edit.html.twig', array('editForm' => $editForm->createView(), ));
    }
}
