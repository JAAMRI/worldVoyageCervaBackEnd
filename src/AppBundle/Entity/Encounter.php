<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use AppBundle\Entity\Patient;
use AppBundle\Entity\User;

/**
 * Encounter
 *
 * @ORM\Table(name="zfzmyrjlmj_encounter")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EncounterRepository")
 * @ExclusionPolicy("all")
 */
class Encounter
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
     * @var \DateTime
     *
     * @Expose
     *
     * @ORM\Column(name="checkin_time", type="datetime", nullable=false)
     */
    private $checkinTime;

    /**
     * @var \DateTime
     *
     * @Expose
     *
     * @ORM\Column(name="checkout_time", type="datetime", nullable=true)
     */
    private $checkoutTime;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="status", type="string", length=155, nullable=false, options={"default":"CHECKED_IN"})
     */
    private $status;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;


    /**
     * @var integer
     *
     * @Expose
     *
     * @ORM\Column(name="post_delay", type="integer", nullable=true, options={"default":"0"})
     */
    private $postDelay;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="encounters")
     *
     * @Expose
     *
     */
    private $provider;


    /**
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Expose
     *
     */
    private $patient;

    /**
     * @ORM\OneToOne(targetEntity="Event")
     *
     * @Expose
     *
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="EncounterProcedure", mappedBy="encounter")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Expose
     *
     */
    private $procedures;

    public function __construct() {
        $this->procedures = new ArrayCollection();
    }

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
     * @return \DateTime
     */
    public function getCheckinTime()
    {
        return $this->checkinTime;
    }

    /**
     * @param \DateTime $checkinTime
     */
    public function setCheckinTime($checkinTime)
    {
        $this->checkinTime = $checkinTime;
    }

    /**
     * @return \DateTime
     */
    public function getCheckoutTime()
    {
        return $this->checkoutTime;
    }

    /**
     * @param \DateTime $checkoutTime
     */
    public function setCheckoutTime($checkoutTime)
    {
        $this->checkoutTime = $checkoutTime;
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

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return int
     */
    public function getPostDelay()
    {
        return $this->postDelay;
    }

    /**
     * @param int $postDelay
     */
    public function setPostDelay($postDelay)
    {
        $this->postDelay = $postDelay;
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
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return EncounterProcedure
     */
    public function getProcedures()
    {
        return $this->procedures;
    }

    /**
     * @param EncounterProcedure $procedures
     */
    public function setProcedures(EncounterProcedure $procedures)
    {
        $this->procedures = $procedures;
    }




}
