<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * PatientBillingDetail
 *
 * @ORM\Table(name="zfzmyrjlmj_patient_billing_detail")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PatientBillingDetailRepository")
 * @ORM\HasLifecycleCallbacks
 * @ExclusionPolicy("all")
 */
class PatientBillingDetail
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
     * @ORM\Column(name="patientUniqueid", type="string", length=32, nullable=false)
     */
    private $patientUniqueid;


    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="type", type="string", nullable=false, length=150)
     */
    private $type;

    /**
     * @var float
     *
     * @Expose
     *
     * @ORM\Column(name="billed", type="float", nullable=true, length=9)
     */
    private $billed;

    /**
     * @var float
     *
     * @Expose
     *
     * @ORM\Column(name="received", type="float", nullable=true, length=9)
     */
    private $received;


    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("paymentMethod")
     *
     * @ORM\Column(name="payment_method", type="string", nullable=true, length=150)
     */
    private $paymentMethod;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="patient_procedure_id", type="integer", length=11, nullable=true)
     */
    private $patientProcedureId;


    /**
     * @var \DateTime
     *
     * @Expose
     *
     * @SerializedName("lastModified")
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified;



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
     * @return int
     */
    public function getPatientUniqueid()
    {
        return $this->patientUniqueid;
    }

    /**
     * @param int $patientUniqueid
     */
    public function setPatientUniqueid($patientUniqueid)
    {
        $this->patientUniqueid = $patientUniqueid;
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
     * @return float
     */
    public function getBilled()
    {
        return $this->billed;
    }

    /**
     * @param float $billed
     */
    public function setBilled($billed)
    {
        $this->billed = $billed;
    }

    /**
     * @return float
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * @param float $received
     */
    public function setReceived($received)
    {
        $this->received = $received;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
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
     * @return int
     */
    public function getPatientProcedureId()
    {
        return $this->patientProcedureId;
    }

    /**
     * @param int $patientProcedureId
     */
    public function setPatientProcedureId($patientProcedureId)
    {
        $this->patientProcedureId = $patientProcedureId;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param \DateTime $lastModified
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;
    }


}
