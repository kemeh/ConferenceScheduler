<?php

namespace ConferenceSchedulerBundle\Controller;

use ConferenceSchedulerBundle\Entity\Session;
use ConferenceSchedulerBundle\Form\SessionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SessionController
 * @Route("session")
 */
class SessionController extends Controller
{
    /**
     * @Route("/create", name="create_session   ")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);

        return $this->render('session/create.html.twig', array('form' => $form->createView()));
    }
}
