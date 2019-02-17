<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * PatientProcedure
 *
 * @ORM\Table(name="zfzmyrjlmj_patient_procedure")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PatientProcedureRepository")
 * @ExclusionPolicy("all")
 * @ORM\HasLifecycleCallbacks

 */
class PatientProcedure
{
    /**
     * @var int
     *
     * @Expose
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
     * @SerializedName("patientUniqueid")
     *
     * @ORM\Column(name="patient_uniqueid", type="string",  nullable=true, length=32, unique=false)
     *
     */
    private $patientUniqueid;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("practitionerUniqueid")
     *
     * @ORM\Column(name="practitioner_uniqueid", type="string",  nullable=true, length=32, unique=false)
     */
    private $practitionerUniqueid;

    /**
     * @var float
     *
     * @Expose
     *
     *
     * @ORM\Column(name="cost", type="float", nullable=false, length=9, options={"default":"0"})
     */
    private $cost;

    /**
     * @var float
     *
     * @Expose
     *
     *
     * @ORM\Column(name="duration", type="float", nullable=true, length=5, options={"default":"0"})
     */
    private $duration;

    /**
     * @var int
     *
     * @Expose
     *
     * @SerializedName("postDelay")
     *
     * @ORM\Column(name="post_delay", type="integer", nullable=false, length=4, options={"default":"0"})
     */
    private $postDelay;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="label", type="string", nullable=false, length=250)
     */
    private $label;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="code", type="string", unique=true, length=200, nullable=true)
     */
    private $code;


    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="status", type="string", nullable=true, length=120)
     */
    private $status;


    /**
     * @var \DateTime
     *
     * @Expose
     *
     *
     * @ORM\Column(name="appointment", type="datetime", nullable=true, unique=false)
     */
    private $appointment;

    /**
     * @var integer
     *
     * @ORM\Column(name="done_by", type="integer", length=12, nullable=true)
     */
    private $addedBy;


    /**
     * @var \DateTime
     *
     * @Expose
     *
     * @SerializedName("doneOn")
     *
     * @ORM\Column(name="done_on", type="datetime", nullable=true, unique=false)
     */
    private $doneOn;


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
    public function getPatientUniqueid()
    {
        return $this->patientUniqueid;
    }

    /**
     * @param string $patientUniqueid
     */
    public function setPatientUniqueid($patientUniqueid)
    {
        $this->patientUniqueid = $patientUniqueid;
    }

    /**
     * @return string
     */
    public function getPractitionerUniqueid()
    {
        return $this->practitionerUniqueid;
    }

    /**
     * @param string $practitionerUniqueid
     */
    public function setPractitionerUniqueid($practitionerUniqueid)
    {
        $this->practitionerUniqueid = $practitionerUniqueid;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
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
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return \DateTime
     */
    public function getAppointment()
    {
        return $this->appointment;
    }

    /**
     * @param \DateTime $appointment
     */
    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * @return int
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * @param int $addedBy
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }

    /**
     * @return \DateTime
     */
    public function getDoneOn()
    {
        return $this->doneOn;
    }

    /**
     * @param \DateTime $doneOn
     */
    public function setDoneOn($doneOn)
    {
        $this->doneOn = $doneOn;
    }



}
