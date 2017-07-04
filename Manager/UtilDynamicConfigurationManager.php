<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 00:29
 */

namespace TechPromux\Bundle\DynamicConfigurationBundle\Manager;


use TechPromux\Bundle\BaseBundle\Manager\BaseManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\DynamicConfiguration;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration;

class UtilDynamicConfigurationManager extends BaseManager
{

    /**
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'TechPromuxDynamicConfigurationBundle';
    }
    
    //-----------------------------------------------------------------------

    /**
     * @var DynamicConfigurationManager
     */
    protected $dynamic_configuration_manager;
    /**
     * @var OwnerConfigurationManager
     */
    protected $owner_configuration_manager;

    /**
     * @return DynamicConfigurationManager
     */
    protected function getDynamicConfigurationManager()
    {
        return $this->dynamic_configuration_manager;
    }

    /**
     * @return OwnerConfigurationManager
     */
    protected function getOwnerConfigurationManager()
    {
        return $this->owner_configuration_manager;
    }

    /**
     * @param DynamicConfigurationManager $dynamic_configuration_manager
     * @return UtilDynamicConfigurationManager
     */
    public function setDynamicConfigurationManager($dynamic_configuration_manager)
    {
        $this->dynamic_configuration_manager = $dynamic_configuration_manager;
        return $this;
    }

    /**
     * @param OwnerConfigurationManager $owner_configuration_manager
     * @return UtilDynamicConfigurationManager
     */
    public function setOwnerConfigurationManager($owner_configuration_manager)
    {
        $this->owner_configuration_manager = $owner_configuration_manager;
        return $this;
    }
    
    //----------------------------------------------------------------------
    /**
     *
     * @param string $code
     * @return DynamicConfiguration|OwnerConfiguration
     */
    public function findConfigurationByCode($code)
    {
        if (is_null($code)) {
            throw new \Exception('Null code dont be accepted');
        }

        $em = $this->getDynamicConfigurationManager()->getDoctrineEntityManager();

        // TODO sustituir por findByCode
        $query = $em->createQuery('SELECT e_ FROM ' . $this->getDynamicConfigurationManager()->getResourceClassShortcut() . ' e_ '
            . 'WHERE e_.name = :code')
            ->setParameter('code', $code);

        $configuration = $query->getOneOrNullResult();
        /* @var $configuration DynamicConfiguration */

        if (is_null($configuration)) {
            throw new \Exception('Configuration code {' . $code . '} was not found');
        }

        if ($configuration->getType() == "SYSTEM") {
            return $configuration;
        } else {
            $owner = $this->getOwnerConfigurationManager()->getResourceOwnerManager()->findOwnerOfAuthenticatedUser();

            $query_cfg = $em->createQuery('SELECT e_ FROM ' . $this->getOwnerConfigurationManager()->getResourceClassShortcut() . ' e_ '
                . 'JOIN e_.owner owner_ JOIN e_.configuration cfg WHERE owner_.id = :owner AND cfg.id = :configuration'
            )
                ->setParameter('owner', $owner->getId())
                ->setParameter('configuration', $configuration->getId());

            $configuration_ov = $query_cfg->getOneOrNullResult();

            if (is_null($configuration_ov)) {
                return $configuration;
            }

            return $configuration_ov;
        }
    }

    /**
     *
     * @param string $code
     * @return any
     */
    public function getConfigurationValueByCode($code)
    {
        $configuration = $this->findConfigurationByCode($code);

        return json_decode($configuration->getValue(), true);
    }
    //---------------------------------------------------------------------------------------------

    /**
     * @return array
     */
    public function getContextTypesChoices()
    {
        return array(
            'SYSTEM' => ('SYSTEM'),
            'OWNER' => ('OWNER'),
            //'USER' => $this->trans('User configuration'),
        );
    }

    //----------------------------------------------------------------------------------------

    protected $dynamic_configuration_types = array();

    /**
     * @param BaseConfigurationType $dynamic_configuration_type
     * @return array
     */
    public function addDynamicConfigurationType($dynamic_configuration_type)
    {
        $this->dynamic_configuration_types[$dynamic_configuration_type->getId()] = $dynamic_configuration_type;
        return $this->dynamic_configuration_types;
    }

    /**
     * @return array
     */
    public function getRegisteredDynamicConfigurationTypes()
    {
        return $this->dynamic_configuration_types;
    }

    /**
     * @return array
     */
    public function getDynamicConfigurationTypesChoices()
    {
        $field_types_choices = array();

        foreach ($this->dynamic_configuration_types as $fto) {
            /** @var $fto BaseConfigurationType */
            $field_types_choices[$fto->getId()] = $fto->getId();
        }
        return $field_types_choices;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getDynamicConfigurationTypeById($type)
    {
        $this->dynamic_configuration_types = $this->getRegisteredDynamicConfigurationTypes();

        return $this->dynamic_configuration_types[$type];
    }

    //--------------------------------------------------------------------------------

    /**
     * @param $object
     * @return array|void
     */
    public function getSettingsOptionsChoices($object)
    {
        $type = $object->getType();

        $object_type = $this->dynamic_configuration_types[$type];
        /** @var $object_type BaseConfigurationType */

        $value_choices = $object_type->getSettingsOptionsChoices($object);

        return $value_choices;
    }

    //----------------------------------------------------------------------

    /**
     * @param DynamicConfiguration $object
     * @return mixed
     */
    public function transformValueToCustom($object)
    {
        $this->dynamic_configuration_types = $this->getRegisteredDynamicConfigurationTypes();

        if ($object && $object->getId()) {
            $type = $object->getType();
            $object_type = $this->dynamic_configuration_types[$type];
            /** @var $object_type BaseConfigurationType */
            $object_type->transformValueToCustom($object);

        }
        return $object;
    }

    public function transformCustomToValue($object)
    {
        $this->dynamic_configuration_types = $this->getRegisteredDynamicConfigurationTypes();

        if ($object && $object->getId()) {
            $type = $object->getType();
            $object_type = $this->dynamic_configuration_types[$type];
            /** @var $object_type BaseConfigurationType */
            $object_type->transformCustomToValue($object);

        }
        return $object;
    }

    //-----------------------------------------------------------
    


}