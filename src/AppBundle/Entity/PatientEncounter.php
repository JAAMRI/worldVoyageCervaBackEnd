<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use AppBundle\Entity\Patient;
use AppBundle\Entity\User;
use AppBundle\Entity\PatientEncounterProcedure;

/**
 * PatientEncounter
 *
 * @ORM\Table(name="zfzmyrjlmj_patient_encounter")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PatientEncounterRepository")
 * @ExclusionPolicy("all")
 */
class PatientEncounter
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
     * @ORM\Column(name="status", type="string", length=55, nullable=false, options={"default":"CHECKED_IN"})
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


    private $provider;

    private $patient;

//    private $procedures;





    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

}
