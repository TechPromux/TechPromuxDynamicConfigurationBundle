<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace TechPromux\Bundle\ConfigurationBundle\Type;


class ImageConfigurationType implements BaseConfigurationType
{

    private $service_container;

    /**
     * @param $service_container
     * @return $this
     */
    public function setServiceContainer($service_container)
    {
        $this->service_container = $service_container;
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
            'context' => 'techpromux_configuration_media_image',//$this->getMediaContext()->getId(),
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

        if (!is_null($value))
        {
            $provider = $this->service_container->get($value->getProviderName());

            $url = $provider->generatePublicUrl($value,'reference');

            $object->setValue(json_encode($url));
        }
        else{
            $object->setValue(null);
        }

        $object->setMedia($value);
        return $object;
    }
}