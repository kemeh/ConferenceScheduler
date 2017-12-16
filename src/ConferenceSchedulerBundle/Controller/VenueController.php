<?php

namespace ConferenceSchedulerBundle\Controller;

use ConferenceSchedulerBundle\Entity\Venue;
use ConferenceSchedulerBundle\Form\VenueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VenueController
 *
 * @Route("venue")
 */
class VenueController extends Controller
{
    /**
     * @Route("/create", name="create_venue")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $venue = new Venue();
        $form = $this->createForm(VenueType::class, $venue);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($venue);
            $em->flush();

            return $this->redirectToRoute('details_venue', array('id' => $venue->getId()));
        }

        return $this->render('venue/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/list", name="list_venues")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $venues = $this->getDoctrine()->getRepository(Venue::class)->findAll();
        return $this->render('venue/list.html.twig', array('venues' => $venues));
    }

    /**
     * @Route("/{id}", name="details_venue")
     * @Method("GET")
     */
    public function detailsAction(Venue $venue)
    {
        return $this->render('venue/details.html.twig', array('venue' => $venue, ));
    }


    /**
     * @Route("/{id}/edit", name="edit_venue")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Venue $venue)
    {
        $editForm = $this->createForm(VenueType::class, $venue);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('details_venue', array('id' => $venue->getId(),));
        }

        return $this->render('venue/edit.html.twig', array('editForm' => $editForm->createView(), ));
    }

    /**
     * @Route("/", name="index_venue")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('list_venues');
    }
}
