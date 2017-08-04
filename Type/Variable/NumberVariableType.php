<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace TechPromux\DynamicConfigurationBundle\Type\Variable;


class NumberVariableType implements BaseVariableType
{

    public function getId()
    {
        return 'number';
    }

    public function getTitle()
    {
        return 'number';
    }

    public function getValueType()
    {
        return 'number';
    }

    public function getValueOptions()
    {
        return array(
            'attr' => array(
                'class' => ''
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
        $value = $object->getValue();
        $custom = json_decode($value);
        $object->setCustomValue($custom);
        return $object;
    }

    public function transformCustomToValue($object)
    {
        $custom = $object->getCustomValue();
        $value = json_encode($custom);
        $object->setValue($value);
        return $object;
    }
}