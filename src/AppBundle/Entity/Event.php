<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\User;
use AppBundle\Entity\Patient;

/**
 * Event
 *
 * @ORM\Table(name="zfzmyrjlmj_event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
{
    /**
     * @var int
     *
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true, unique=false)
     */
    private $title;


    /**
     * @var \DateTime
     *
     * @Expose
     *
     * @ORM\Column(name="start", type="datetime", nullable=false, unique=false)
     */
    private $start ;

    /**
     * @var \DateTime
     *
     * @Expose
     *
     * @ORM\Column(name="end", type="datetime", nullable=false, unique=false)
     */
    private $end;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="color", type="string", length=7, nullable=true, unique=false)
     */
    private $color;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="lastname", type="string", length=44, nullable=true, unique=false)
     */
    private $lastname;


    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="phone", type="string", length=55, nullable=true, unique=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="type", type="string", length=80, nullable=false, unique=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provider;

    /**
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $patient;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="procedures", type="text", nullable=true, unique=false)
     */
    private $procedures;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="status", type="string", length=155, nullable=false, options={"default":"CONFIRMED"})
     */
    private $status;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return User
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param User $provider
     */
    public function setProvider(User $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * @param Patient $patient
     */
    public function setPatient(Patient $patient)
    {
        $this->patient = $patient;
    }

    /**
     * @return string
     */
    public function getProcedures()
    {
        return $this->procedures;
    }

    /**
     * @param string $procedures
     */
    public function setProcedures($procedures)
    {
        $this->procedures = $procedures;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}
