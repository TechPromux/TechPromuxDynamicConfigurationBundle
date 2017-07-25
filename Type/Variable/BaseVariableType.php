<?php

namespace  TechPromux\DynamicConfigurationBundle\Type\Variable;

/**
 * Interface BaseVariableType
 *
 * @package  TechPromux\DynamicDynamicConfigurationBundle\Type\Variable
 */
interface BaseVariableType
{
    public function getId();

    public function getTitle();

    public function getValueType();

    public function getValueOptions();

    public function getHasSettings();

    public function getSettingsType();

    public function getSettingsOptions();

    public function getSettingsOptionsChoices($object);

    public function transformValueToCustom($object);

    public function transformCustomToValue($object);

}