<?php

namespace ConferenceSchedulerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Hall
 *
 * @ORM\Table(name="halls")
 * @ORM\Entity(repositoryClass="ConferenceSchedulerBundle\Repository\HallRepository")
 */
class Hall
{
    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="capacity", type="integer")
     */
    private $capacity;

    /**
     * @ORM\ManyToOne(targetEntity="ConferenceSchedulerBundle\Entity\Venue", inversedBy="halls")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     */
    private $venue;

    /**
     * @ORM\OneToMany(targetEntity="ConferenceSchedulerBundle\Entity\Session", mappedBy="hall")
     */
    private $sessions;

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
     * Set name
     *
     * @param string $name
     *
     * @return Hall
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     *
     * @return Hall
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }
}

