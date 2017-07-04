<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\DynamicConfiguration;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\BaseConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\BooleanConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ChoiceCheckboxDifferentKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ChoiceCheckboxSameKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ChoiceRadioDifferentKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ChoiceRadioSameKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ChoiceSelectDifferentKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ChoiceSelectSameKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ColorRGBAConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ColorRGBConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\CountryConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\DateConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\DatetimeConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\EmailConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\ImageConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\IntegerConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\LanguageConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\LocaleConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleBooleanConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleColorRGBAConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleColorRGBConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleCountryConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleDateConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleDatetimeConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleEmailConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleIntegerConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleKeyValueConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleLanguageConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleLocaleConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleNumberConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleTextareaConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleTextareaHtmlConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleTextConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleTimeConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleTimezoneConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\MultipleUrlConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\NumberConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\TextareaConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\TextareaHtmlConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\TextConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\TimeConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\TimezoneConfigurationType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\UrlConfigurationType;

/**
 * Class DynamicConfigurationManager
 *
 * @package TechPromux\Bundle\DynamicDynamicConfigurationBundle\Manager
 */
class DynamicConfigurationManager extends BaseResourceManager {

    public function getBundleName() {
        return 'TechPromuxDynamicConfigurationBundle';
    }

    public function getMediaContextId()
    {
        return 'techpromux_dynamic_configuration_media_image';
    }

    /**
     * Obtiene la clase de la entidad
     *
     * @return class
     */
    public function getResourceClass()
    {
        return DynamicConfiguration::class;
    }

    /**
     * Obtiene el nombre corto de la entidad
     *
     * @return string
     */
    public function getResourceName()
    {
        return 'DynamicConfiguration';
    }

    //------------------------------------------------------------------------

    /**
     * @var UtilDynamicConfigurationManager
     */
    protected $util_dynamic_configuration_manager;

    /**
     * @return mixed
     */
    public function getUtilDynamicConfigurationManager()
    {
        return $this->util_dynamic_configuration_manager;
    }

    /**
     * @param mixed $util_dynamic_configuration_manager
     * @return DynamicConfigurationManager
     */
    public function setUtilDynamicConfigurationManager($util_dynamic_configuration_manager)
    {
        $this->util_dynamic_configuration_manager = $util_dynamic_configuration_manager;
        return $this;
    }



    //--------------------------------------------------------------------------------

    /**
     * @param DynamicConfiguration $object
     */
    public function preUpdate($object, $flushed = true)
    {
        if ($object->getContext() == 'SYSTEM') {
            $object->getOwnerConfigurations()->clear();
        }
        parent::preUpdate($object, $flushed);
    }

    //-------------------------------------------------------------------------------------

}
