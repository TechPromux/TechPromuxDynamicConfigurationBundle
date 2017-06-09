<?php

namespace TechPromux\Bundle\ConfigurationBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\ConfigurationBundle\Entity\CustomConfiguration;
use TechPromux\Bundle\ConfigurationBundle\Type\BaseConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\BooleanConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ChoiceCheckboxDifferentKeyValueConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ChoiceCheckboxSameKeyValueConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ChoiceRadioDifferentKeyValueConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ChoiceRadioSameKeyValueConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ChoiceSelectDifferentKeyValueConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ChoiceSelectSameKeyValueConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ColorRGBAConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ColorRGBConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\CountryConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\DateConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\DatetimeConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\EmailConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\ImageConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\IntegerConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\LanguageConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\LocaleConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleBooleanConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleColorRGBAConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleColorRGBConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleCountryConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleDateConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleDatetimeConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleEmailConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleIntegerConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleKeyValueConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleLanguageConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleLocaleConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleNumberConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleTextareaConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleTextareaHtmlConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleTextConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleTimeConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleTimezoneConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\MultipleUrlConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\NumberConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\TextareaConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\TextareaHtmlConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\TextConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\TimeConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\TimezoneConfigurationType;
use TechPromux\Bundle\ConfigurationBundle\Type\UrlConfigurationType;

/**
 * Class CustomConfigurationManager
 *
 * @package TechPromux\Bundle\ConfigurationBundle\Manager
 */
class CustomConfigurationManager extends BaseResourceManager {

    public function getBundleName() {
        return 'TechPromuxConfigurationBundle';
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
