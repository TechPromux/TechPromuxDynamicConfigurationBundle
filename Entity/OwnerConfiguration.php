<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TechPromux\Bundle\BaseBundle\Entity\Resource\BaseResource;
use TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\ResourceOwner;
use TechPromux\Bundle\BaseBundle\Entity\Resource\Owner\HasResourceOwner;

/**
 * OwnerConfiguration
 *
 * @ORM\Table(name="techpromux_dynamic_configuration_owner_configuration")
 * @ORM\Entity()
 */
class OwnerConfiguration extends BaseResource implements HasResourceOwner
{

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var \Sonata\MediaBundle\Entity\BaseMedia
     *
     * @ORM\ManyToOne(targetEntity="Sonata\MediaBundle\Entity\BaseMedia",cascade={"all"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    protected $media;

    /**
     * @ORM\ManyToOne(targetEntity="TechPromux\Bundle\DynamicConfigurationBundle\Entity\DynamicConfiguration", inversedBy="ownerConfigurations")
     * @ORM\JoinColumn(name="configuration_id", referencedColumnName="id", nullable=false)
     */
    private $configuration;

    /**
     * @var ConfigurationResourceOwner
     *
     * @ORM\ManyToOne(targetEntity="ConfigurationResourceOwner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=true)
     */
    protected $owner;

    //----------------------------------------------------------------------------------------------------------

    /**
     * Set configuration
     *
     * @param \TechPromux\Bundle\DynamicConfigurationBundle\Entity\DynamicConfiguration $configuration
     *
     * @return OwnerConfiguration
     */
    public function setConfiguration(\TechPromux\Bundle\DynamicConfigurationBundle\Entity\DynamicConfiguration $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Get configuration
     *
     * @return \TechPromux\Bundle\DynamicConfigurationBundle\Entity\DynamicConfiguration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return ConfigurationResourceOwner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param ConfigurationResourceOwner $owner
     * @return OwnerConfiguration
     */
    public function setOwner(ResourceOwner $owner = null)
    {
        $this->owner = $owner;
        return $this;
    }

    //----------------------------------------------------------------------------------------------

    /**
     * Get code
     *
     * @return string
     */
    public function getName()
    {
        return $this->getConfiguration() ? $this->getConfiguration()->getName() : null;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfiguration() ? $this->getConfiguration()->getDescription() : null;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getConfiguration() ? $this->getConfiguration()->getType() : null;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return OwnerConfiguration
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

    //public function getHashCode() {
    //    return parent::getHashCode() .'-'. ($this->getConfiguration()->getType() == 'image' && $this->media ? $this->media->getName() : $this->value);
    //}

    public function __toString()
    {
        return $this->getTitle() ? $this->getTitle() : '';
    }

    /**
     * Get value
     *
     * @return string
     */

    public function getPrintableValue()
    {
        if ($this->getType() == "image") {
            return $this->media ? $this->media : $this->getConfiguration()->getMedia();
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
