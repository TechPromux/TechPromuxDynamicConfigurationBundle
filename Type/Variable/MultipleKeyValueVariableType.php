<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace TechPromux\DynamicConfigurationBundle\Type\Variable;


class MultipleKeyValueVariableType implements BaseVariableType
{

    public function getId()
    {
        return 'multiple-key-value';
    }

    public function getTitle()
    {
        return 'multiple-key-value';
    }

    public function getValueType()
    {
        return 'sonata_type_native_collection';
    }

    public function getValueOptions()
    {
        return array(
            'entry_type' => 'sonata_type_immutable_array', //sonata_type_immutable_array
            'allow_add' => true, // TODO $this->isSuperAdminAuthenticated(),
            'allow_delete' => true, // TODO $this->isSuperAdminAuthenticated(),
            'entry_options' => array(
                'required' => true,
                'attr' => array('class' => ''),
                'keys' => array(
                    array('key', 'text', array(
                        'label' => 'Key',
                        'disabled' => false, // TODO !$this->isSuperAdminAuthenticated(),
                        "label_attr" => array('data-ctype-modify' => 'parent', 'data-ctype-modify-parent-addclass' => 'col-md-4'),
                    )
                    ),
                    array('value', 'text', array(
                        'label' => 'Value',
                        "label_attr" => array('data-ctype-modify' => 'parent', 'data-ctype-modify-parent-addclass' => 'col-md-8'),
                    )
                    ),
                ),
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
        $value = $object->getValue() ? $object->getValue() : '[]';

        $key_values = array();

        foreach (json_decode($value, true) as $k => $v) {
            $key_values[] = array('key' => $k, 'value' => $v);
            //$key_values[$k] = $v;
        }

        $custom = $key_values;

        $object->setCustomValue($custom);

        return $object;
    }

    public function transformCustomToValue($object)
    {
        $custom = $object->getCustomValue();

        $keys_values = array();

        foreach ($custom as $kv) {
            $keys_values[$kv['key']] = $kv['value'];
        }
        $value = json_encode($keys_values, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_BIGINT_AS_STRING | JSON_OBJECT_AS_ARRAY);

        $object->setValue($value);
        return $object;
    }
}