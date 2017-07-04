<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration;

/**
 * Interface BaseConfigurationType
 *
 * @package TechPromux\Bundle\DynamicDynamicConfigurationBundle\Type\Configuration
 */
interface BaseConfigurationType
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