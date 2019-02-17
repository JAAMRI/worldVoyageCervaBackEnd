<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DefaultLanguage
 *
 * @ORM\Table(name="zfzmyrjlmj_default_language")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultLanguageRepository")
 */
class DefaultLanguage
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
     * @ORM\Column(name="label", type="string", length=35, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="code_iso", type="string", length=6, nullable=false)
     */
    private $codeIso;


    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default":"1"})
     */
    private $active;



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
    public function getCodeIso()
    {
        return $this->codeIso;
    }

    /**
     * @param string $codeIso
     */
    public function setCodeIso($codeIso)
    {
        $this->codeIso = $codeIso;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }






}
