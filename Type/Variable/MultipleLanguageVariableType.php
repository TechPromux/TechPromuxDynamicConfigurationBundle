<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace TechPromux\DynamicConfigurationBundle\Type\Variable;


class MultipleLanguageVariableType implements BaseVariableType
{

    public function getId()
    {
        return 'multiple-language';
    }

    public function getTitle()
    {
        return 'multiple-language';
    }

    public function getValueType()
    {
        return 'sonata_type_native_collection';
    }

    public function getValueOptions()
    {
        return array(
            'entry_type' => 'language', //sonata_type_immutable_array
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => array(
                'required' => false,
                'attr' => array('class' => ''),
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