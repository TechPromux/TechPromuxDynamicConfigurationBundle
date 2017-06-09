<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace TechPromux\Bundle\ConfigurationBundle\Type;


class TimeConfigurationType implements BaseConfigurationType
{

    public function getId()
    {
        return 'time';
    }

    public function getTitle()
    {
        return 'time';
    }

    public function getValueType()
    {
        return 'sonata_type_datetime_picker';
    }

    public function getValueOptions()
    {
        return array(
            //'read_only' => false,
            'format' => 'HH:mm:ss',
            'dp_use_seconds' => true,
            'dp_default_date' => (new \DateTime())->format('Y-m-d H:i:s'),
            'attr' => array(
                'class' => 'time-picker'
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
        if (is_null($object->getValue())) {
            return null;
        }

        $value = json_decode($object->getValue());

        $custom = \DateTime::createFromFormat('Y-m-d H:i:s', $value);

        $object->setCustomValue($custom);

        return $object;
    }

    public function transformCustomToValue($object)
    {
        $custom = $object->getCustomValue();

        $value = null;

        if ($custom instanceof \DateTime) {
            $current_timezone = (new \DateTime())->getTimezone();
            $custom->setTimezone($current_timezone);
            $value = json_encode($custom->format('Y-m-d H:i:s'));
        } else {
            $value = json_encode($custom);
        }

        $object->setValue($value);

        return $object;
    }
}