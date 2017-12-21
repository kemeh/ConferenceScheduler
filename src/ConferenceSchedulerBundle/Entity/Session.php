<?php

namespace ConferenceSchedulerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="sessions")
 * @ORM\Entity(repositoryClass="ConferenceSchedulerBundle\Repository\SessionRepository")
 */
class Session
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
     * @ORM\Column(name="start_time", type="datetime")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime")
     */
    private $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="ConferenceSchedulerBundle\Entity\Hall", inversedBy="sessions")
     * @ORM\JoinColumn(name="hall_id", referencedColumnName="id")
     */
    private $hall;

    /**
     * @ORM\ManyToOne(targetEntity="ConferenceSchedulerBundle\Entity\User", inversedBy="ledSessions")
     * @ORM\JoinColumn(name="speaker_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $speaker;

    /**
     * @ORM\ManyToOne(targetEntity="ConferenceSchedulerBundle\Entity\Conference", inversedBy="sessions")
     * @ORM\JoinColumn(name="conference_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $conference;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", options={"default" = true})
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="ConferenceSchedulerBundle\Entity\User", mappedBy="participatedSessions")
     */
    private $participants;

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function addParticipant(User $participant)
    {
        $this->participants[] = $participant;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getConference()
    {
        return $this->conference;
    }

    /**
     * @param mixed $conference
     */
    public function setConference($conference)
    {
        $this->conference = $conference;
    }

    /**
     * @return mixed
     */
    public function getHall()
    {
        return $this->hall;
    }

    /**
     * @param mixed $hall
     */
    public function setHall($hall)
    {
        $this->hall = $hall;
    }

    /**
     * @return mixed
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * @param mixed $speaker
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
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
     * @return Session
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
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Session
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return Session
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @return bool
     */
    public function overlaps($sessions)
    {
        $overlaps = false;
        foreach ($sessions as $session){
            if($this->getStartTime() < $session->getEndTime() && $session->getStartTime() < $this->getEndTime()){
                $overlaps = true;
                break;
            }
        }

        return $overlaps;
    }
}

