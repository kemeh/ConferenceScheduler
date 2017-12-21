<?php

namespace ConferenceSchedulerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invitation
 *
 * @ORM\Table(name="invitations")
 * @ORM\Entity(repositoryClass="ConferenceSchedulerBundle\Repository\InvitationRepository")
 */
class Invitation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var bool
     *
     * @ORM\Column(name="isResponded", type="boolean", options={"default" = false})
     */
    private $isResponded;

    /**
     * @ORM\ManyToOne(targetEntity="ConferenceSchedulerBundle\Entity\User", inversedBy="invitations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="ConferenceSchedulerBundle\Entity\Session")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $session;

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Invitation
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set isResponded
     *
     * @param boolean $isResponded
     */
    public function setIsResponded($isResponded)
    {
        $this->isResponded = $isResponded;
    }

    /**
     * Get isResponded
     *
     * @return bool
     */
    public function getIsResponded()
    {
        return $this->isResponded;
    }

    public function fill(Session $session)
    {
        $this->setMessage('You have been invited to lead a lecture');
        $this->setUser($session->getSpeaker());
        $this->setSession($session);
        $this->setIsResponded(false);

        return $this;
    }
}

