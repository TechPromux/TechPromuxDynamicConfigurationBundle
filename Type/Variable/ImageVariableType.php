<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable;


use TechPromux\Bundle\DynamicConfigurationBundle\Manager\DynamicVariableManager;

class ImageVariableType implements BaseVariableType
{

    private $service_container;

    /**
     * @var DynamicVariableManager
     */
    private $dynamic_variable_manager;

    /**
     * @param $service_container
     * @return $this
     */
    public function setServiceContainer($service_container)
    {
        $this->service_container = $service_container;
        return $this;
    }

    /**
     * @return DynamicVariableManager
     */
    public function getDynamicVariableManager()
    {
        return $this->dynamic_variable_manager;
    }

    /**
     * @param DynamicVariableManager $dynamic_variable_manager
     * @return ImageVariableType
     */
    public function setDynamicVariableManager($dynamic_variable_manager)
    {
        $this->dynamic_variable_manager = $dynamic_variable_manager;
        return $this;
    }

    public function getId()
    {
        return 'image';
    }

    public function getTitle()
    {
        return 'image';
    }

    public function getValueType()
    {
        return 'sonata_media_type';
    }

    public function getValueOptions()
    {
        return array(
            'provider' => 'sonata.media.provider.image',
            'context' => $this->getDynamicVariableManager()->getMediaContext()->getId(),
            'attr' => array(
                'class' => 'type_media_image'
            )
        );
    }

    public function getHasSettings()
    {
        return false;
    }

    public function getSettingsType()
    {
        return null;
    }

    public function getSettingsOptions()
    {
        return null;
    }

    public function getSettingsOptionsChoices($object)
    {
        return null;
    }

    public function transformValueToCustom($object)
    {
        $value = $object->getMedia();
        $custom = $value;
        $object->setCustomValue($custom);
        return $object;
    }

    public function transformCustomToValue($object)
    {
        $custom = $object->getCustomValue();
        $value = $custom;

        if (!is_null($value)) {
            $provider = $this->service_container->get($value->getProviderName());

            $url = $provider->generatePublicUrl($value, 'reference');

            $object->setValue(json_encode($url));
        } else {
            $object->setValue(null);
        }

        $object->setMedia($value);
        return $object;
    }
}