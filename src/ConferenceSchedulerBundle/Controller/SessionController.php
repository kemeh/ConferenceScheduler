<?php

namespace ConferenceSchedulerBundle\Controller;

use ConferenceSchedulerBundle\Entity\Conference;
use ConferenceSchedulerBundle\Entity\Invitation;
use ConferenceSchedulerBundle\Entity\Session;
use ConferenceSchedulerBundle\Form\SessionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SessionController
 * @Route("session")
 * @Security("has_role('ROLE_USER')")
 */
class SessionController extends Controller
{
    /**
     * @Route("/create/{conference_id}", name="create_session", methods={"POST", "GET"})
     * @var Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, int $conference_id)
    {
        $conferenceRepo = $this->getDoctrine()->getRepository(Conference::class);
        $conference = $conferenceRepo->find($conference_id);

        $session = new Session();
        $form = $this->createForm(SessionType::class, $session, array(
            'venueHalls' => $conference->getVenue()->getHalls(),
        ));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $session->setConference($conference);
            $session->setIsActive(true);

            $em = $this->getDoctrine()->getManager();
            if($session->getCategory() == 'lecture'){
                $session->setIsActive(false);

                $invitation = new Invitation();
                $invitation = $invitation->fill($session);

                $em->persist($invitation);
            }

            $em->persist($session);
            $em->flush();

            $this->addFlash("success", "You have created new Session");
            return $this->redirectToRoute('details_conference', array(
                'id' => $conference_id,
                '_fragment' => 'B',)
            );
        }

        return $this->render('session/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/{id}", name="signup_for_session")
     * @param Request $request
     */
    public function signUpAction(Request $request, Session $session)
    {
        $form = $this->createFormBuilder()->
        add('signup', SubmitType::class)->
        getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(count($session->getParticipants()) == $session->getHall()->getCapacity()){
                throw new Exception("go fuck yourself");
            }
            $this->getUser()->addParticipatedSessions($session);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash("success", "You have signed up for " . $session->getTopic());
            return $this->redirectToRoute('details_conference', array(
                'id' => $session->getConference()->getId(),
                '_fragment' => 'B',
            ));
        }

        return $this->render('session/signup.html.twig', array('form' => $form->createView()));
    }
}
