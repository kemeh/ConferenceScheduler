<?php

namespace ConferenceSchedulerBundle\Controller;

use ConferenceSchedulerBundle\Entity\Conference;
use ConferenceSchedulerBundle\Form\ConferenceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ConferenceController
 * @Route("conference")
 */
class ConferenceController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/create", name="create_conference")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $owner = $this->getUser();
            $conference->setOwner($owner);

            $em = $this->getDoctrine()->getManager();
            $em->persist($conference);
            $em->flush();

            return $this->redirectToRoute('details_conference', array('id' => $conference->getId()));
        }

        return $this->render('conference/create.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/list", name="list_conferences")
     * @Method("GET")
     * @return Response
     */
    public function listAction()
    {
        $conferences = $this->getDoctrine()->getRepository(Conference::class)->findAll();
        return $this->render('conference/list.html.twig', array('conferences' => $conferences));
    }

    /**
     * @Route("/{id}", name="details_conference")
     * @Method("GET")
     */
    public function detailsAction(Conference $conference)
    {
        return $this->render('conference/details.html.twig', array('conference' => $conference));
    }

}
