<?php

namespace TechPromux\DynamicConfigurationBundle\Manager;

use TechPromux\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\DynamicConfigurationBundle\Entity\DynamicVariable;
use TechPromux\DynamicConfigurationBundle\Type\Variable\BaseVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\BooleanVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ChoiceCheckboxDifferentKeyValueVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ChoiceCheckboxSameKeyValueVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ChoiceRadioDifferentKeyValueVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ChoiceRadioSameKeyValueVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ChoiceSelectDifferentKeyValueVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ChoiceSelectSameKeyValueVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ColorRGBAVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ColorRGBVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\CountryVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\DateVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\DatetimeVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\EmailVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\ImageVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\IntegerVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\LanguageVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\LocaleVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleBooleanVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleColorRGBAVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleColorRGBVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleCountryVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleDateVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleDatetimeVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleEmailVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleIntegerVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleKeyValueVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleLanguageVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleLocaleVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleNumberVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleTextareaVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleTextareaHtmlVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleTextVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleTimeVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleTimezoneVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\MultipleUrlVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\NumberVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\TextareaVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\TextareaHtmlVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\TextVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\TimeVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\TimezoneVariableType;
use TechPromux\DynamicConfigurationBundle\Type\Variable\UrlVariableType;

/**
 * Class DynamicVariableManager
 *
 * @package  TechPromux\DynamicDynamicConfigurationBundle\Manager
 */
class DynamicVariableManager extends BaseResourceManager
{

    public function getBundleName()
    {
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
     * @return UtilDynamicConfigurationManager
     */
    public function getUtilDynamicConfigurationManager()
    {
        return $this->util_dynamic_configuration_manager;
    }

    /**
     * @param UtilDynamicConfigurationManager $util_dynamic_configuration_manager
     * @return DynamicVariableManager
     */
    public function setUtilDynamicConfigurationManager($util_dynamic_configuration_manager)
    {
        $this->util_dynamic_configuration_manager = $util_dynamic_configuration_manager;
        return $this;
    }

    //---------------------------------------------------------------------------

    /**
     *
     * @param string $name
     * @return DynamicVariable|ContextVariable
     */
    protected function findVariableByName($name)
    {
        if (is_null($name)) {
            throw new \Exception('Null name don´t be accepted');
        }

        $variable = $this->findOneBy(array('name' => $name));
        /* @var $variable DynamicVariable */

        return $variable;
    }

    /**
     *
     * @param string $name
     * @return any
     */
    public function getVariableValueByName($name)
    {
        $variable = $this->findVariableByName($name);

        return json_decode($variable->getValue(), true);
    }

}
