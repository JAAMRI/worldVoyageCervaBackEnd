<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * DefaultProcedure
 *
 * @ORM\Table(name="zfzmyrjlmj_default_procedure")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DefaultProcedureRepository")
 * @ExclusionPolicy("all")
 */
class DefaultProcedure
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
     * @var float
     *
     * @Expose
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
     * @ORM\Column(name="post_delay", type="integer", nullable=true, length=4, options={"default":"0"})
     */
    private $postDelay;


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
     * @ORM\Column(name="code", type="string", unique=true, length=200, nullable=true)
     */
    private $code;


    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default":"1"})
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="order", type="integer", length=2, nullable=false, options={"default":"0"})
     */
    private $order;

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

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }


}
