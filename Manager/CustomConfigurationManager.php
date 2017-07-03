<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\CustomConfiguration;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\BaseConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\BooleanConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ChoiceCheckboxDifferentKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ChoiceCheckboxSameKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ChoiceRadioDifferentKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ChoiceRadioSameKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ChoiceSelectDifferentKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ChoiceSelectSameKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ColorRGBAConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ColorRGBConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\CountryConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\DateConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\DatetimeConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\EmailConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\ImageConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\IntegerConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\LanguageConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\LocaleConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleBooleanConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleColorRGBAConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleColorRGBConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleCountryConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleDateConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleDatetimeConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleEmailConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleIntegerConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleLanguageConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleLocaleConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleNumberConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleTextareaConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleTextareaHtmlConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleTextConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleTimeConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleTimezoneConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\MultipleUrlConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\NumberConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\TextareaConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\TextareaHtmlConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\TextConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\TimeConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\TimezoneConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\UrlConfigurationType;

/**
 * Class CustomConfigurationManager
 *
 * @package TechPromux\Bundle\DynamicDynamicConfigurationBundle\Manager
 */
class CustomConfigurationManager extends BaseResourceManager {

    public function getBundleName() {
        return 'TechPromuxDynamicConfigurationBundle';
    }

    public function getMediaContextId()
    {
        return 'techpromux_configuration_media_image';
    }

    /**
     * Obtiene la clase de la entidad
     *
     * @return class
     */
    public function getResourceClass()
    {
        return CustomConfiguration::class;
    }

    /**
     * Obtiene el nombre corto de la entidad
     *
     * @return string
     */
    public function getResourceName()
    {
        return 'CustomConfiguration';
    }

    //--------------------------------------------------------------------------------

    /**
     * @param CustomConfiguration $object
     */
    public function preUpdate($object, $flushed = true)
    {
        if ($object->getContext() == 'SYSTEM') {
            $object->getOwnerConfigurations()->clear();
        }
        parent::preUpdate($object, $flushed);
    }

    //-------------------------------------------------------------------------------------

    /**
     * @return array
     */
    public function getContextTypesChoices()
    {
        return array(
            'SYSTEM' => ('SYSTEM'),
            'OWNER' => ('OWNER'),
            //'USER' => $this->trans('User configuration'),
        );
    }

    //----------------------------------------------------------------------------------------

    protected $registered_custom_configuration_types = array();

    /**
     * @param BaseConfigurationType $custom_configuration_type
     * @return array
     */
    public function addCustomConfigurationType($custom_configuration_type)
    {
        $this->registered_custom_configuration_types[$custom_configuration_type->getId()] = $custom_configuration_type;
        return $this->registered_custom_configuration_types;
    }

    /**
     * @return array
     */
    public function getRegisteredCustomConfigurationTypes()
    {
        return $this->registered_custom_configuration_types;
    }

    /**
     * @return array
     */
    public function getCustomConfigurationTypesChoices()
    {
        $field_types_choices = array();

        foreach ($this->registered_custom_configuration_types as $fto) {
            /** @var $fto BaseConfigurationType */
            $field_types_choices[$fto->getId()] = $fto->getId();
        }
        return $field_types_choices;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getCustomConfigurationTypeById($type)
    {
        $this->registered_custom_configuration_types = $this->getRegisteredCustomConfigurationTypes();

        return $this->registered_custom_configuration_types[$type];
    }

    //--------------------------------------------------------------------------------

    /**
     * @param $type
     * @param $object
     * @return array|void
     */
    public function getSettingsOptionsChoices($object)
    {
        $type = $object->getType();

        $object_type = $this->registered_custom_configuration_types[$type];
        /** @var $object_type BaseConfigurationType */

        $value_choices = $object_type->getSettingsOptionsChoices($object);

        return $value_choices;
    }

    //----------------------------------------------------------------------

    /**
     * @param CustomConfiguration $object
     * @return mixed
     */
    public function transformValueToCustom($object)
    {
        $this->registered_custom_configuration_types = $this->getRegisteredCustomConfigurationTypes();

        if ($object && $object->getId()) {
            $type = $object->getType();
            $object_type = $this->registered_custom_configuration_types[$type];
            /** @var $object_type BaseConfigurationType */
            $object_type->transformValueToCustom($object);

        }
        return $object;
    }

    public function transformCustomToValue($object)
    {
        $this->registered_custom_configuration_types = $this->getRegisteredCustomConfigurationTypes();

        if ($object && $object->getId()) {
            $type = $object->getType();
            $object_type = $this->registered_custom_configuration_types[$type];
            /** @var $object_type BaseConfigurationType */
            $object_type->transformCustomToValue($object);

        }
        return $object;
    }


}
