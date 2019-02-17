<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use AppBundle\Entity\User;
use AppBundle\Entity\Encounter;

/**
 * EncounterProcedure
 *
 * @ORM\Table(name="zfzmyrjlmj_encounter_procedure")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EncounterProcedureRepository")
 * @ExclusionPolicy("all")
 */
class EncounterProcedure
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
     * @var float
     *
     * @Expose
     *
     * @ORM\Column(name="cost", type="float", nullable=false, length=9, options={"default":"0"})
     */
    private $cost;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="label", type="string", length=250, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="code", type="string", unique=false, length=200, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="status", type="string", length=155, nullable=true, options={"default":"UNDONE"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     *
     * @Expose
     *
     */
    private $provider;

    /**
     * @ORM\ManyToOne(targetEntity="Encounter", inversedBy="procedures")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Expose
     *
     */
    private $encounter;


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
     * @return Encounter
     */
    public function getEncounter()
    {
        return $this->encounter;
    }

    /**
     * @param Encounter $encounter
     */
    public function setEncounter(Encounter $encounter)
    {
        $this->encounter = $encounter;
    }



}
