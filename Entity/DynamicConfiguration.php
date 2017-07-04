<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource;
use Translatable\Fixture\Type\Custom;

/**
 * DynamicConfiguration
 *
 * @ORM\Table(name="techpromux_dynamic_configuration_dynamic_configuration")
 * @ORM\Entity()
 */
class DynamicConfiguration extends BaseResource
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
     * @ORM\Column(name="context", type="string", length=255)
     */
    private $context;

    /**
     * @var \Sonata\MediaBundle\Entity\BaseMedia
     *
     * @ORM\ManyToOne(targetEntity="Sonata\MediaBundle\Entity\BaseMedia",cascade={"all"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    /**
     * @ORM\OneToMany(targetEntity="TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration", mappedBy="configuration", cascade={"all"}, orphanRemoval=true)
     */
    private $ownerConfigurations;

    //-------------------------------------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ownerConfigurations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ownerConfiguration
     *
     * @param \TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration $ownerConfiguration
     *
     * @return DynamicConfiguration
     */
    public function addOwnerConfiguration(\TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration $ownerConfiguration)
    {
        $this->ownerConfigurations[] = $ownerConfiguration;

        return $this;
    }

    /**
     * Remove ownerConfiguration
     *
     * @param \TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration $ownerConfiguration
     */
    public function removeOwnerConfiguration(\TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration $ownerConfiguration)
    {
        $this->ownerConfigurations->removeElement($ownerConfiguration);
    }

    /**
     * Get ownerConfigurations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOwnerConfigurations()
    {
        return $this->ownerConfigurations;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return DynamicConfiguration
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
     * @return DynamicConfiguration
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
     * @return DynamicConfiguration
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
     * @param string $context
     *
     * @return DynamicConfiguration
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
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
     * @return DynamicConfiguration
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
     * @return DynamicConfiguration
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
        $printable_value = json_decode($this->value,true);

        if (is_array($printable_value))
            return $this->value;
        return $printable_value;
    }

}
