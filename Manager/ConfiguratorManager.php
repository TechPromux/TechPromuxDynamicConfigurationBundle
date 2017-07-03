<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 00:29
 */

namespace TechPromux\Bundle\DynamicConfigurationBundle\Manager;


use TechPromux\Bundle\BaseBundle\Manager\BaseManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\CustomConfiguration;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration;

class ConfiguratorManager extends BaseManager
{
    /**
     * @var CustomConfigurationManager
     */
    protected $custom_configuration_manager;
    /**
     * @var OwnerConfigurationManager
     */
    protected $owner_configuration_manager;

    /**
     * @return CustomConfigurationManager
     */
    protected function getCustomConfigurationManager()
    {
        return $this->custom_configuration_manager;
    }

    /**
     * @return OwnerConfigurationManager
     */
    protected function getOwnerConfigurationManager()
    {
        return $this->owner_configuration_manager;
    }

    /**
     * @param CustomConfigurationManager $custom_configuration_manager
     * @return ConfiguratorManager
     */
    public function setCustomConfigurationManager($custom_configuration_manager)
    {
        $this->custom_configuration_manager = $custom_configuration_manager;
        return $this;
    }

    /**
     * @param OwnerConfigurationManager $owner_configuration_manager
     * @return ConfiguratorManager
     */
    public function setOwnerConfigurationManager($owner_configuration_manager)
    {
        $this->owner_configuration_manager = $owner_configuration_manager;
        return $this;
    }

    /**
     *
     * @param string $code
     * @return CustomConfiguration|OwnerConfiguration
     */
    public function findConfigurationByCode($code)
    {
        if (is_null($code)) {
            throw new \Exception('Null code dont be accepted');
        }

        $em = $this->getCustomConfigurationManager()->getDoctrineEntityManager();

        // TODO sustituir por findByCode
        $query = $em->createQuery('SELECT e_ FROM ' . $this->getCustomConfigurationManager()->getResourceClassShortcut() . ' e_ '
            . 'WHERE e_.name = :code')
            ->setParameter('code', $code);

        $configuration = $query->getOneOrNullResult();
        /* @var $configuration CustomConfiguration */

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

    /**
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'TechPromuxDynamicConfigurationBundle';
    }
}