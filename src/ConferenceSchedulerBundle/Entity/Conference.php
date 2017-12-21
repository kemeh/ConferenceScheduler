<?php

namespace ConferenceSchedulerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Conference
 *
 * @ORM\Table(name="conferences")
 * @ORM\Entity(repositoryClass="ConferenceSchedulerBundle\Repository\ConferenceRepository")
 */
class Conference
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
     * @ORM\Column(name="topic", type="string", length=255)
     */
    private $topic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=2000)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="ConferenceSchedulerBundle\Entity\Venue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $venue;

    /**
     * @ORM\ManyToOne(targetEntity="ConferenceSchedulerBundle\Entity\User", inversedBy="ownedConferences")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $owner;


    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ConferenceSchedulerBundle\Entity\User", inversedBy="administratedConferences")
     * @ORM\JoinTable(name="users_administrated_conferences",
     *     joinColumns={@ORM\JoinColumn(name="conference_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    private $administrators;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ConferenceSchedulerBundle\Entity\Session", mappedBy="conference")
     * @ORM\OrderBy({"startTime" = "ASC"})
     */
    private $sessions;

    public function __construct()
    {
        $this->administrators = new ArrayCollection();
        $this->sessions = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    /**
     * @param mixed $sessions
     */
    public function setSessions($sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdministrators()
    {
        return $this->administrators;
    }

    public function addAdministrator(User $admin)
    {
        $admin->addAdministratedConference($this);
        $this->administrators[] = $admin;
    }

    /**
     * @return mixed
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @param mixed $venue
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;
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
     * Set topic
     *
     * @param string $topic
     *
     * @return Conference
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Conference
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Conference
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}

