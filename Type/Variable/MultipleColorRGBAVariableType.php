<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace  TechPromux\DynamicConfigurationBundle\Type\Variable;


class MultipleColorRGBAVariableType implements BaseVariableType
{

    public function getId()
    {
        return 'multiple-color-rgba';
    }

    public function getTitle()
    {
        return 'multiple-color-rgba';
    }

    public function getValueType()
    {
        return 'sonata_type_native_collection';
    }

    public function getValueOptions()
    {
        return array(
            'entry_type' => 'text', //sonata_type_immutable_array
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => array(
                'empty_data' => 'rgba(0,0,255)',
                'attr' => array(
                    'class' => 'color-picker-rgba',
                ),
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
        $value = $object->getValue()?$object->getValue():'[]';
        $custom = json_decode($value );
        $object->setCustomValue($custom);
        return $object;
    }

    public function transformCustomToValue($object)
    {
        $custom = $object->getCustomValue()?$object->getCustomValue():array();
        $value = json_encode(array_values($custom), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_BIGINT_AS_STRING | JSON_OBJECT_AS_ARRAY);
        $object->setValue($value);
        return $object;
    }
}