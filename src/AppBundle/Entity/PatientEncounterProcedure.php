<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatientEncounterProcedure
 *
 * @ORM\Table(name="patient_encounter_procedure")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PatientEncounterProcedureRepository")
 */
class PatientEncounterProcedure
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
