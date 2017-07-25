<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace  TechPromux\DynamicConfigurationBundle\Type\Variable;


class ChoiceSelectDifferentKeyValueVariableType implements BaseVariableType
{

    public function getId()
    {
        return 'choice-select-different-key-value';
    }

    public function getTitle()
    {
        return 'choice-select-different-key-value';
    }

    public function getValueType()
    {
        return 'choice';
    }

    public function getValueOptions()
    {
        return array(
            //'label' => 'Choose Value',
            'multiple' => false,
            'expanded' => false,
            'required' => false,
            'attr' => array(
                'class' => ''
            )
        );
    }

    public function getHasSettings()
    {
        return true;
    }

    public function getSettingsType()
    {
        return 'sonata_type_native_collection';
    }

    public function getSettingsOptions()
    {
        return array(
            'entry_type' => 'sonata_type_immutable_array',
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => array(
                'required' => false,
                'attr' => array('class' => ''),
                'keys' => array(
                    array('id', 'text', array('label' => 'key', "label_attr" => array('data-ctype-modify' => 'parent', 'data-ctype-modify-parent-addclass' => 'col-md-5'))),
                    array('text', 'text', array('label' => 'text', "label_attr" => array('data-ctype-modify' => 'parent', 'data-ctype-modify-parent-addclass' => 'col-md-7')))
                )
            ),
        );
    }

    public function getSettingsOptionsChoices($object)
    {
        $settings_choices = $object->getSettings();

        $value_choices = array();
        foreach (array_values($settings_choices) as $sch) {
            $value_choices[$sch['text']] = $sch['id'];
        }
        return $value_choices;
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