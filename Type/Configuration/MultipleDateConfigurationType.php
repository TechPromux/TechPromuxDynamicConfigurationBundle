<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration;


class MultipleDateConfigurationType implements BaseConfigurationType
{

    public function getId()
    {
        return 'multiple-date';
    }

    public function getTitle()
    {
        return 'multiple-date';
    }

    public function getValueType()
    {
        return 'sonata_type_native_collection';
    }

    public function getValueOptions()
    {
        return array(
            'entry_type' => 'sonata_type_date_picker', //sonata_type_immutable_array
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => array(
                //'read_only' => false,
                'format' => 'yyyy-MM-dd',
                'dp_default_date' => (new \DateTime())->format('Y-m-d'),
                'attr' => array(
                    'class' => 'date-picker'
                )
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
        $datetime_values = array();
        foreach (json_decode($value) as $dt) {
            $datetime_values[] = \DateTime::createFromFormat('Y-m-d H:i:s', $dt);
        }
        $custom = $datetime_values;
        $object->setCustomValue($custom);
        return $object;
    }

    public function transformCustomToValue($object)
    {
        $custom = $object->getCustomValue()?$object->getCustomValue():array();
        $datetime_values = array();
        $current_timezone = (new \DateTime())->getTimezone();
        foreach ($custom as $dt) {
            $dt->setTimezone($current_timezone);
            $datetime_values[] = $dt->format('Y-m-d H:i:s');
        }
        $value = json_encode(array_values($datetime_values), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_BIGINT_AS_STRING | JSON_OBJECT_AS_ARRAY);
        $object->setValue($value);
        return $object;
    }
}