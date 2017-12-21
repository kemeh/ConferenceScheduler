<?php

namespace ConferenceSchedulerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="ConferenceSchedulerBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100)
     */
    private $lastName;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ConferenceSchedulerBundle\Entity\Role")
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *     )
     */
    private $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ConferenceSchedulerBundle\Entity\Conference", mappedBy="owner")
     */
    private $ownedConferences;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ConferenceSchedulerBundle\Entity\Conference", mappedBy="administrators")
     */
    private $administratedConferences;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ConferenceSchedulerBundle\Entity\Session", inversedBy="participants")
     * @ORM\JoinTable(name="users_sessions",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="session_id", referencedColumnName="id", onDelete="CASCADE")})
     * @ORM\OrderBy({"startTime" = "ASC"})
     */
    private $participatedSessions;

    /**
     * @ORM\OneToMany(targetEntity="ConferenceSchedulerBundle\Entity\Session", mappedBy="speaker")
     */
    private $ledSessions;

    /**
     * @ORM\OneToMany(targetEntity="ConferenceSchedulerBundle\Entity\Invitation", mappedBy="user")
     */
    private $invitations;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->ownedConferences = new ArrayCollection();
        $this->administratedConferences = new ArrayCollection();
        $this->ledSessions = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipatedSessions()
    {
        return $this->participatedSessions;
    }

    /**
     * @param ArrayCollection $participatedSessions
     */
    public function addParticipatedSessions(Session $participatedSession)
    {
        $participatedSession->addParticipant($this);
        $this->participatedSessions[] = $participatedSession;
    }

    /**
     * @return mixed
     */
    public function getInvitations()
    {
        return $this->invitations;
    }

    /**
     * @param mixed $invitations
     */
    public function setInvitations($invitation)
    {
        $this->invitations[] = $invitation;
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName(). ' ' . $this->getLastName();
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $stringRoles = [];
        foreach ($this->roles as $role){
            /** @var Role $role */
            $stringRoles[] = $role->getRole();
        }

        return $stringRoles;
    }

    /**
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function addAdministratedConference(Conference $conference)
    {
        $this->administratedConferences[] = $conference;
    }

    /**
     * @param Conference $conference
     * @return bool
     */
    public function isConferenceOwner(Conference $conference)
    {
        return $conference->getOwner()->getId() == $this->getId();
    }

    /**
     * @param Conference $conference
     * @return bool
     */
    public function isConferenceAdmin(Conference $conference)
    {
        return in_array($this->getEmail(), $conference->getAdministrators()->toArray());
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return in_array('ROLE_SITE_ADMIN', $this->getRoles());
    }

    public function __toString()
    {
        return $this->getEmail();
    }

}

