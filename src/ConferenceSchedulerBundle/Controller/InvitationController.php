<?php

namespace ConferenceSchedulerBundle\Controller;

use ConferenceSchedulerBundle\Entity\Invitation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvitationController
 * @Route("invitation")
 * @Security("has_role('ROLE_USER')")
 */
class InvitationController extends Controller
{

    /**
     * @Route("/response/{id}", name="invitation_response")
     * @var Request $request
     * @return Response
     */
    public function responseAction(Request $request, Invitation $invitation)
    {
        $form = $this->createResponseForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $invitation->setIsResponded(true);
            $em = $this->getDoctrine()->getManager();

            if($form->get('reject')->isClicked()){
                $em->remove($invitation->getSession());
            }

            $invitation->getSession()->setIsActive(true);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $invitation->getUser()->getId()));
        }

        return $this->render('invitation/response.html.twig', array('form' => $form->createView()));
    }

    private function createResponseForm()
    {
        return $this->createFormBuilder()->
        add('accept', SubmitType::class, array(
            'label' => 'Accept',
            'attr' => ['class' => 'btn btn-success']
            )
        )->
        add('reject', SubmitType::class, array(
            'label' => 'Reject',
            'attr' => ['class' => 'btn btn-danger']
            )
        )->
        getForm();
    }
}
