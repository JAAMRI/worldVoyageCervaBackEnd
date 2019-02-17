<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * PatientBillingStatement
 *
 * @ORM\Table(name="zfzmyrjlmj_patient_billing_statement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PatientBillingStatementRepository")
 * @ORM\HasLifecycleCallbacks
 * @ExclusionPolicy("all")
 */
class PatientBillingStatement
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
     *
     * @ORM\Column(name="amount", type="float", nullable=false, length=9, options={"default":"0"})
     */
    private $amount;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("paymentMethod")
     *
     * @ORM\Column(name="payment_method", type="string", nullable=false, length=150)
     */
    private $paymentMethod;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @Expose
     *
     * @SerializedName("createdOn")
     *
     * @ORM\Column(name="created_on", type="datetime", nullable=true)
     */
    private $createdOn;

    /**
     * @var int
     *
     * @ORM\Column(name="patient_procedure_id", type="integer", length=11, nullable=true)
     */
    private $patientProcedureId;


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
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = new \DateTime("now");
    }




}
