<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZfzmyrjlmjCurrency
 *
 * @ORM\Table(name="zfzmyrjlmj_currency")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ZfzmyrjlmjCurrencyRepository")
 */
class ZfzmyrjlmjCurrency
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
     * @ORM\Column(name="code_iso", type="string", length=3, nullable=false)
     */
    private $codeIso;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=64, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="unicode", type="string", length=32, nullable=true)
     */
    private $unicode;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
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
    public function getUnicode()
    {
        return $this->unicode;
    }

    /**
     * @param string $unicode
     */
    public function setUnicode($unicode)
    {
        $this->unicode = $unicode;
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
