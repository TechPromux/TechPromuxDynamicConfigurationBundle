<?php

namespace TechPromux\DynamicConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\BaseBundle\Entity\Resource\BaseResource;

/**
 * DynamicVariable
 *
 * @ORM\Table(name="techpromux_dynamic_configuration_dynamic_variable")
 * @ORM\Entity()
 */
class DynamicVariable extends BaseResource
{
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var any
     *
     */
    private $customValue;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var json
     *
     * @ORM\Column(name="settings", type="json")
     */
    private $settings;

    /**
     * @var string
     *
     * @ORM\Column(name="context_type", type="string", length=255)
     */
    private $contextType;

    /**
     * @var \Sonata\MediaBundle\Entity\BaseMedia
     *
     * @ORM\ManyToOne(targetEntity="Sonata\MediaBundle\Entity\BaseMedia",cascade={"all"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    //-------------------------------------------------------------------------------------

    /**
     * Set value
     *
     * @param string $value
     *
     * @return DynamicVariable
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return DynamicVariable
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set settings
     *
     * @param json $settings
     *
     * @return DynamicVariable
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Get settings
     *
     * @return json
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set context
     *
     * @param string $context_type
     *
     * @return DynamicVariable
     */
    public function setContextType($context_type)
    {
        $this->contextType = $context_type;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContextType()
    {
        return $this->contextType;
    }

    //-----------------------------------------------------------------------------------------------------------

    public function __toString()
    {
        return $this->getTitle() ? $this->getTitle() : '';
    }

    /**
     * Set media
     *
     * @param \Sonata\MediaBundle\Entity\BaseMedia $media
     *
     * @return DynamicVariable
     */
    public function setMedia(\Sonata\MediaBundle\Entity\BaseMedia $media = null)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * Get media
     *
     * @return \Sonata\MediaBundle\Entity\BaseMedia
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @return any
     */
    public function getCustomValue()
    {
        return $this->customValue;
    }

    /**
     * @param any $customValue
     * @return DynamicVariable
     */
    public function setCustomValue($customValue)
    {
        $this->customValue = $customValue;
        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */

    public function getPrintableValue()
    {
        if ($this->getType() == "image") {
            return $this->media;
        }
        if ($this->getType() == "boolean") {
            return $this->value;
        }
        $printable_value = json_decode($this->value, true);

        if (is_array($printable_value))
            return $this->value;
        return $printable_value;
    }

}
