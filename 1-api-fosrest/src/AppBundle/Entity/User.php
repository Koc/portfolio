<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="guard_user")
 * @ORM\Entity
 * @UniqueEntity("email", groups={"Default", "Registration"})
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"Default", "Registration"})
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"Default", "Registration"})
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"Default", "Registration"})
     * @Assert\Email(groups={"Default", "Registration"})
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     */
    protected $algorithm;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     */
    protected $salt;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     */
    protected $password;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $isActive;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $isConfirmed;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $isSuperAdmin;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $lastLogin;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var UserProfile
     */
    protected $userProfile;

    /**
     * @var UserProfile
     */
    protected $newUserProfile;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->newUserProfile = new UserProfile();
        $this->algorithm = 'sha1';
    }

    /**
     * @ORM\PostPersist()
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if ($this->newUserProfile) {
            $this->userProfile = $this->newUserProfile;
            $this->userProfile->setUser($this);
            $args->getEntityManager()->persist($this->userProfile);
            $args->getEntityManager()->flush();
            $this->newUserProfile = null;
        }
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;
        $this->setUsername($email);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = (string)$username;
    }

    /**
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @param string $algorithm
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * @param bool $isConfirmed
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->isSuperAdmin;
    }

    /**
     * @param bool $isSuperAdmin
     */
    public function setIsSuperAdmin($isSuperAdmin)
    {
        $this->isSuperAdmin = $isSuperAdmin;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return UserProfile|null
     */
    public function getUserProfile()
    {
        if ($this->userProfile) {
            return $this->userProfile;
        }

        if ($this->em) {
            return $this->userProfile = $this->em->find(UserProfile::class, $this->id);
        }
    }

    /**
     * @param UserProfile $userProfile
     */
    public function setUserProfile(UserProfile $userProfile)
    {
        $this->userProfile = $userProfile;
    }

    /**
     * @return UserProfile
     */
    public function getNewUserProfile()
    {
        return $this->newUserProfile;
    }

    /**
     * @param UserProfile $newUserProfile
     */
    public function setNewUserProfile(UserProfile $newUserProfile)
    {
        $this->newUserProfile = $newUserProfile;
    }

    /**
     * Returns the roles granted to the user.
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Removes sensitive data from the user.
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }
}
