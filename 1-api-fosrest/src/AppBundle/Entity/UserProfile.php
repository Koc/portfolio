<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user_profile")
 * @ORM\Entity
 */
class UserProfile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id", referencedColumnName="id", nullable=false)
     * @var User
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CompanyType")
     * @ORM\JoinColumn(name="company_type_id", referencedColumnName="id")
     * @Assert\NotBlank()
     * @var CompanyType
     */
    protected $companyType;

    /**
     * @var \Doctrine\Common\Collections\Collection|CompanyActivity[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\CompanyActivity")
     * @ORM\JoinTable(
     *  name="company_activity_ref_profile",
     *  joinColumns={
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="activity_id", referencedColumnName="id")
     *  }
     * )
     */
    protected $companyActivities;

    /**
     * @ORM\Column(type="string", length=255, name="director")
     * @Assert\NotBlank()
     * @var string
     */
    protected $director;

    /**
     * @ORM\Column(type="string", length=255, name="company_email")
     * @var string
     */
    protected $companyEmail;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     *
     * @var Region
     */
    protected $region;

    /**
     * @var string
     * @ORM\Column(type="string", length=2)
     * @Assert\NotBlank()
     * @Assert\Length(max="2", min="2")
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=255, name="phone")
     * @Assert\NotBlank()
     * @var string
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255, name="fax")
     * @var string
     */
    protected $fax;

    /**
     * @ORM\Column(type="string", length=255, name="url")
     * @var string
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=2000)
     * @var string
     */
    protected $address;

    /**
     * @ORM\Column(type="bigint", name="nom_vnut")
     * @var int
     */
    protected $nomVnut;

    /**
     * @ORM\Column(type="string", length=2000)
     * @Assert\Length(max="2000")
     * @var string
     */
    protected $activities;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected $isPublic;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    protected $isSubscribed;

    /**
     * UserProfile constructor.
     */
    public function __construct()
    {
        $this->company = '';
        $this->director = '';
        $this->companyEmail = '';
        $this->phone = '';
        $this->fax = '';
        $this->url = '';
        $this->address = '';
        $this->activities = '';
        $this->isPublic = false;
        $this->companyActivities = new ArrayCollection();
    }

    /**
     * @return User
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer|User $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->id;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->id = $user;
    }


    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = (string)$company;
    }

    /**
     * @return CompanyType
     */
    public function getCompanyType()
    {
        return $this->companyType;
    }

    /**
     * @param CompanyType $companyType
     */
    public function setCompanyType(CompanyType $companyType)
    {
        $this->companyType = $companyType;
    }

    /**
     * @return string
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * @param string $director
     */
    public function setDirector($director)
    {
        $this->director = (string)$director;
    }

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param Region $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = (string)$country;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = (string)$phone;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = (string)$fax;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = (string)$address;
    }

    /**
     * @return int
     */
    public function getNomVnut()
    {
        return $this->nomVnut;
    }

    /**
     * @param int $nomVnut
     */
    public function setNomVnut($nomVnut)
    {
        $this->nomVnut = $nomVnut;
    }

    /**
     * @return string
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * @param string $activities
     */
    public function setActivities($activities)
    {
        $this->activities = (string)$activities;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param bool $isPublic
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
    }

    /**
     * @return string
     */
    public function getCompanyEmail()
    {
        return $this->companyEmail;
    }

    /**
     * @param string $companyEmail
     */
    public function setCompanyEmail($companyEmail)
    {
        $this->companyEmail = (string)$companyEmail;
    }

    /**
     * @return CompanyActivity[]|\Doctrine\Common\Collections\Collection
     */
    public function getCompanyActivities()
    {
        return $this->companyActivities;
    }

    /**
     * @param CompanyActivity[]|\Doctrine\Common\Collections\Collection $companyActivities
     */
    public function setCompanyActivities($companyActivities)
    {
        $this->companyActivities = $companyActivities;
    }

    /**
     * @return bool
     */
    public function isSubscribed()
    {
        return $this->isSubscribed;
    }

    /**
     * @param bool $isSubscribed
     */
    public function setIsSubscribed($isSubscribed)
    {
        $this->isSubscribed = $isSubscribed;
    }
}
