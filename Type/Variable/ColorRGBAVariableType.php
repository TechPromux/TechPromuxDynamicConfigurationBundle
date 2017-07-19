<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace TechPromux\Bundle\DynamicConfigurationBundle\Type\Variable;


class ColorRGBAVariableType implements BaseVariableType
{

    public function getId()
    {
        return 'color-rgba';
    }

    public function getTitle()
    {
        return 'color-rgba';
    }

    public function getValueType()
    {
        return 'text';
    }

    public function getValueOptions()
    {
        return array(
            'empty_data' => 'rgba(0,0,0,0)',
            'attr' => array(
                'class' => 'color-picker-rgba',
            ),
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