<?php

namespace ConferenceSchedulerBundle\Controller;

use ConferenceSchedulerBundle\Entity\Conference;
use ConferenceSchedulerBundle\Entity\Session;
use ConferenceSchedulerBundle\Entity\User;
use ConferenceSchedulerBundle\Entity\Venue;
use ConferenceSchedulerBundle\Form\ConferenceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ConferenceController
 * @Route("conference")
 */
class ConferenceController extends Controller
{
    /**
     * @Route("/create", name="create_conference")
     * @param Request $request
     * @return Response
     * @Security("has_role('ROLE_USER')"))
     */
    public function createAction(Request $request)
    {
        $venueRepo = $this->getDoctrine()->getRepository(Venue::class);
        $venues = $venueRepo->findAll();

        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference, array(
            'venues' => $venues,
            ));

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
        $conferences = $this->getDoctrine()->getRepository(Conference::class)->getActiveConferences();
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

    /**
     * @Route("/{id}/admins", name="add_admins")
     * @var Request $request
     * @return Response
     * @Security("has_role('ROLE_USER')")
     */
    public function addAdminsAction(Request $request, Conference $conference)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if(!$currentUser->isConferenceOwner($conference) && !$currentUser->isAdmin()){
            throw new AccessDeniedException();
        }

        $form = $this->createAdminsAddForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $administrators = $data['administrators'];
            foreach($administrators as $admin){
                $conference->addAdministrator($admin);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($conference);
            $em->flush();

            return $this->redirectToRoute('details_conference', array('id' => $conference->getId(), '_fragment' => 'C'));
        }

        return $this->render('conference/admins.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/{id}/delete", name="delete_conference")
     * @var Request $request
     * @return Response
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, Conference $conference)
    {
        /* @var User $currentUser */
        $currentUser = $this->getUser();

        if(!$currentUser->isAdmin() || !$currentUser->isConferenceOwner($conference)){
            throw new AccessDeniedException();
        }

        $form = $this->createFormBuilder()->
        add('delete', SubmitType::class)->
        getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($conference);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('conference/delete.html.twig', array('form' => $form->createView()));
    }

    private function createAdminsAddForm()
    {
        return $this->createFormBuilder()->
        add('administrators', EntityType::class, array(
            'multiple' => true,
            'class' => User::class,
            'choice_label' => 'getEmail',
        ))
            ->
            add('Add', SubmitType::class)->
            getForm();
    }

}
