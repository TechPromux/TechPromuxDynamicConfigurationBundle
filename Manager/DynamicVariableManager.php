<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\DynamicVariable;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\BaseVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\BooleanVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ChoiceCheckboxDifferentKeyValueVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ChoiceCheckboxSameKeyValueVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ChoiceRadioDifferentKeyValueVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ChoiceRadioSameKeyValueVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ChoiceSelectDifferentKeyValueVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ChoiceSelectSameKeyValueVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ColorRGBAVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ColorRGBVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\CountryVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\DateVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\DatetimeVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\EmailVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\ImageVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\IntegerVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\LanguageVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\LocaleVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleBooleanVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleColorRGBAVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleColorRGBVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleCountryVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleDateVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleDatetimeVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleEmailVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleIntegerVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleKeyValueVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleLanguageVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleLocaleVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleNumberVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleTextareaVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleTextareaHtmlVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleTextVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleTimeVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleTimezoneVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\MultipleUrlVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\NumberVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\TextareaVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\TextareaHtmlVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\TextVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\TimeVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\TimezoneVariableType;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable\UrlVariableType;

/**
 * Class DynamicVariableManager
 *
 * @package TechPromux\Bundle\DynamicDynamicConfigurationBundle\Manager
 */
class DynamicVariableManager extends BaseResourceManager {

    public function getBundleName() {
        return 'TechPromuxDynamicConfigurationBundle';
    }

    public function getMediaContextId()
    {
        return 'techpromux_dynamic_configuration_media_image';
    }

    /**
     * Get entity class name
     *
     * @return class
     */
    public function getResourceClass()
    {
        return DynamicVariable::class;
    }

    /**
     * Get entity short name
     *
     * @return string
     */
    public function getResourceName()
    {
        return 'DynamicVariable';
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
     * @return DynamicVariableManager
     */
    public function setUtilDynamicConfigurationManager($util_dynamic_configuration_manager)
    {
        $this->util_dynamic_configuration_manager = $util_dynamic_configuration_manager;
        return $this;
    }



    //--------------------------------------------------------------------------------

    /**
     * @param DynamicVariable $object
     */
    public function preUpdate($object, $flushed = true)
    {
        if ($object->getContext() == 'SYSTEM') {
            $object->getOwnerVariables()->clear();
        }
        parent::preUpdate($object, $flushed);
    }

    //-------------------------------------------------------------------------------------

}
