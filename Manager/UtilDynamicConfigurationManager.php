<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 00:29
 */

namespace TechPromux\DynamicConfigurationBundle\Manager;


use TechPromux\BaseBundle\Manager\BaseManager;
use TechPromux\DynamicConfigurationBundle\Entity\DynamicVariable;
use TechPromux\DynamicConfigurationBundle\Entity\ContextVariable;

class UtilDynamicConfigurationManager extends BaseManager
{

    /**
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'TechPromuxDynamicConfigurationBundle';
    }

    //-----------------------------------------------------------------------

    /**
     * @var DynamicVariableManager
     */
    protected $dynamic_configuration_manager;

    /**
     * @return DynamicVariableManager
     */
    protected function getDynamicVariableManager()
    {
        return $this->dynamic_configuration_manager;
    }

    /**
     * @param DynamicVariableManager $dynamic_configuration_manager
     * @return UtilDynamicConfigurationManager
     */
    public function setDynamicVariableManager($dynamic_configuration_manager)
    {
        $this->dynamic_configuration_manager = $dynamic_configuration_manager;
        return $this;
    }

    //---------------------------------------------------------------------------------------------

    /**
     * @return array
     */
    public function getContextTypesChoices()
    {
        return array(
            'SYSTEM' => ('SYSTEM'),
            'SHARED' => ('SHARED'),
        );
    }

    //----------------------------------------------------------------------------------------

    protected $variable_types = array();

    /**
     * @param $variable_type
     * @return $this
     */
    public function addVariableType($variable_type)
    {
        $this->variable_types[$variable_type->getId()] = $variable_type;
        return $this;
    }

    /**
     * @return array
     */
    public function getRegisteredVariableTypes()
    {
        return $this->variable_types;
    }

    /**
     * @return array
     */
    public function getVariableTypesChoices()
    {
        $types_choices = array();

        foreach ($this->variable_types as $fto) {
            /** @var $fto BaseVariableType */
            $types_choices[$fto->getId()] = $fto->getId();
        }
        return $types_choices;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getVariableTypeById($type)
    {
        return $this->variable_types[$type];
    }

    //--------------------------------------------------------------------------------

    /**
     * @param $object
     * @return array|void
     */
    public function getSettingsOptionsChoices($object)
    {
        $type = $object->getType();

        $object_type = $this->getVariableTypeById($type);
        /** @var $object_type BaseVariableType */

        $value_choices = $object_type->getSettingsOptionsChoices($object);

        return $value_choices;
    }

    //----------------------------------------------------------------------

    /**
     * @param DynamicVariable $object
     * @return mixed
     */
    public function transformValueToCustom($object)
    {
        if ($object && $object->getId()) {
            $type = $object->getType();
            $variable_type = $this->getVariableTypeById($type);
            /** @var $variable_type BaseVariableType */
            $variable_type->transformValueToCustom($object);

        }
        return $object;
    }

    public function transformCustomToValue($object)
    {
        if ($object && $object->getId()) {
            $type = $object->getType();
            $variable_type = $this->getVariableTypeById($type);
            /** @var $variable_type BaseVariableType */
            $variable_type->transformCustomToValue($object);

        }
        return $object;
    }

    //-----------------------------------------------------------


}