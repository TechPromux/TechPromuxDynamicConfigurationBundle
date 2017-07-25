<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 00:29
 */

namespace  TechPromux\DynamicConfigurationBundle\Manager;


use  TechPromux\BaseBundle\Manager\BaseManager;
use  TechPromux\DynamicConfigurationBundle\Entity\DynamicVariable;
use  TechPromux\DynamicConfigurationBundle\Entity\OwnerVariable;

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
     * @var DynamicVariableManager
     */
    protected $dynamic_configuration_manager;
    /**
     * @var OwnerVariableManager
     */
    protected $owner_variable_manager;

    /**
     * @return DynamicVariableManager
     */
    protected function getDynamicVariableManager()
    {
        return $this->dynamic_configuration_manager;
    }

    /**
     * @return OwnerVariableManager
     */
    protected function getOwnerVariableManager()
    {
        return $this->owner_variable_manager;
    }

    /**
     * @param DynamicVariableManager $dynamic_configuration_manager
     * @return UtilDynamicConfigurationManager
     */
    public function setDynamicVariableManager($dynamic_configuration_manager)
    {
        $this->dynamic_configuration_manager = $dynamic_configuration_manager;
        return $this;
    }

    /**
     * @param OwnerVariableManager $owner_variable_manager
     * @return UtilDynamicConfigurationManager
     */
    public function setOwnerVariableManager($owner_variable_manager)
    {
        $this->owner_variable_manager = $owner_variable_manager;
        return $this;
    }

    //----------------------------------------------------------------------

    /**
     *
     * @param string $code
     * @return DynamicVariable|OwnerVariable
     */
    public function findVariableByCode($code)
    {
        if (is_null($code)) {
            throw new \Exception('Null code dont be accepted');
        }

        $em = $this->getDynamicVariableManager()->getEntityManager();

        // TODO sustituir por findByCode
        $query = $em->createQuery('SELECT e_ FROM ' . $this->getDynamicVariableManager()->getResourceClassShortcut() . ' e_ '
            . 'WHERE e_.name = :code')
            ->setParameter('code', $code);

        $variable = $query->getOneOrNullResult();
        /* @var $variable DynamicVariable */

        if (is_null($variable)) {
            throw new \Exception('Variable code {' . $code . '} was not found');
        }

        if ($variable->getType() == "SYSTEM") {
            return $variable;
        } else {
            $owner = $this->getOwnerVariableManager()->getResourceOwnerManager()->findOwnerOfAuthenticatedUser();

            $query_cfg = $em->createQuery('SELECT e_ FROM ' . $this->getOwnerVariableManager()->getResourceClassShortcut() . ' e_ '
                . 'JOIN e_.owner owner_ JOIN e_.variable cfg WHERE owner_.id = :owner AND cfg.id = :variable'
            )
                ->setParameter('owner', $owner->getId())
                ->setParameter('variable', $variable->getId());

            $variable_ov = $query_cfg->getOneOrNullResult();

            if (is_null($variable_ov)) {
                return $variable;
            }

            return $variable_ov;
        }
    }

    /**
     *
     * @param string $code
     * @return any
     */
    public function getVariableValueByCode($code)
    {
        $variable = $this->findVariableByCode($code);

        return json_decode($variable->getValue(), true);
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
            //'USER' => $this->trans('User variable'),
        );
    }

    //----------------------------------------------------------------------------------------

    protected $variable_types = array();

    /**
     * @param $variable_type
     * @return $this
     */
    public function addVariableType($variable_type)
    {
        $this->variable_types[$variable_type->getId()] = $variable_type;
        return $this;
    }

    /**
     * @return array
     */
    public function getRegisteredVariableTypes()
    {
        return $this->variable_types;
    }

    /**
     * @return array
     */
    public function getVariableTypesChoices()
    {
        $types_choices = array();

        foreach ($this->variable_types as $fto) {
            /** @var $fto BaseVariableType */
            $types_choices[$fto->getId()] = $fto->getId();
        }
        return $types_choices;
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getVariableTypeById($type)
    {
        return $this->variable_types[$type];
    }

    //--------------------------------------------------------------------------------

    /**
     * @param $object
     * @return array|void
     */
    public function getSettingsOptionsChoices($object)
    {
        $type = $object->getType();

        $object_type = $this->getVariableTypeById($type);
        /** @var $object_type BaseVariableType */

        $value_choices = $object_type->getSettingsOptionsChoices($object);

        return $value_choices;
    }

    //----------------------------------------------------------------------

    /**
     * @param DynamicVariable $object
     * @return mixed
     */
    public function transformValueToCustom($object)
    {
        if ($object && $object->getId()) {
            $type = $object->getType();
            $object_type = $this->variable_types[$type];
            /** @var $object_type BaseVariableType */
            $object_type->transformValueToCustom($object);

        }
        return $object;
    }

    public function transformCustomToValue($object)
    {
        if ($object && $object->getId()) {
            $type = $object->getType();
            $object_type = $this->variable_types[$type];
            /** @var $object_type BaseVariableType */
            $object_type->transformCustomToValue($object);

        }
        return $object;
    }

    //-----------------------------------------------------------


}