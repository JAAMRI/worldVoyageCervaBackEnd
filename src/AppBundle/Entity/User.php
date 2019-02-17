<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * User
 *
 * @ORM\Table("users")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     * @Assert\Length(
     *      min = 7,
     *      groups={"registration", "login","updateuser"}
     * )
     * @Expose
     *
     * @ORM\Column(name="phone", type="string", length=55, nullable=false, unique=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="title", type="string", length=12, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 45,
     *      minMessage = "Firstname must be at least {{ limit }} characters long",
     *      maxMessage = "Firstname cannot be longer than {{ limit }} characters",
     *      groups={"updateuser"}
     * )
     * @Assert\NotBlank(
     *      message = "Firstname can not be blank ",
     *      groups={"updateuser"}
     * )
     *
     * @Expose
     *
     * @ORM\Column(name="firstname", type="string", length=45, nullable=false, unique=false, options={"default":""})
     */
    private $firstname;



    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 45,
     *      minMessage = "Lastname name must be at least {{ limit }} characters long",
     *      maxMessage = "Lastname cannot be longer than {{ limit }} characters",
     *      groups={"updateuser"}
     * )
     * @Assert\NotBlank(
     *      message = "Lastname can not be blank",
     *      groups={"updateuser"}
     * )
     *
     * @Expose
     *
     * @ORM\Column(name="lastname", type="string", length=45, nullable=false, unique=false, options={"default":""})
     */
    private $lastname;




    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="profileimg", type="string", nullable=true)
     */
    private $profileimg;


    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "City name must be at least {{ limit }} characters long",
     *      maxMessage = "City name cannot be longer than {{ limit }} characters",
     *      groups={"registration", "updateuser"}
     * )
     *
     * @Expose
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true, unique=false)
     */
    private $city;


    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="color", type="string", length=7, nullable=false, unique=false, options={"default":"#8fbad0"})
     */
    private $color;


    /**
     * @var string
     *
     * @Assert\Regex(
     *      pattern="/^[-+]?([0-9]:?[0]?[0]?|1[0-4]:?[0]?[0]?|[3,4,5,6,9,10,11]:30|[5,8]:45|12:45)$/",
     *      message="Please enter a valid UTC Offset Value",
     *      groups={"registration", "updateuser"}
     * )
     * @Assert\NotBlank(
     *      message = "UTC OFFSET is a required field",
     *      groups={"registration", "updateuser"}
     * )
     *
     * @ORM\Column(name="utcoffset", type="string",length=10, nullable=false, unique=false, options={"default":"0"})
     */
    private $utcoffset;


    /**
     * @var \DateTime
     *
     *
     * @ORM\Column(name="createdon", type="datetime", nullable=false, unique=false)
     */
    private $createdon;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="provider")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="Encounter", mappedBy="provider")
     */
    private $encounters;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false, options={"default":"1"})
     */
    private $isActive;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }


    /**
     * @return string
     */
    public function getProfileimg()
    {
        return $this->profileimg;
    }

    /**
     * @param string $profileimg
     */
    public function setProfileimg($profileimg)
    {
        $this->profileimg = $profileimg;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getUtcoffset()
    {
        return $this->utcoffset;
    }

    /**
     * @param string $utcoffset
     */
    public function setUtcoffset($utcoffset)
    {
        $this->utcoffset = $utcoffset;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setUserUniqueid()
    {
        $this->userUniqueid = uniqid();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedon()
    {
        return $this->createdon;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedon()
    {
        $this->createdon = new \DateTime();
    }

    /**
     * @return boolean
     */
    public function isIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

}