<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration;

/**
 * ConfigurationManager
 *
 */
class OwnerConfigurationManager extends BaseResourceManager {

    public function getBundleName() {
        return 'TechPromuxDynamicConfigurationBundle';
    }

    public function getMediaContextId()
    {
        return 'techpromux_dynamic_configuration_media_image';
    }

    /**
     * Obtiene la clase de la entidad
     *
     * @return class
     */
    public function getResourceClass()
    {
        return OwnerConfiguration::class;
    }

    /**
     * Obtiene el nombre corto de la entidad
     *
     * @return string
     */
    public function getResourceName()
    {
        return 'OwnerConfiguration';
    }

    //------------------------------------------

    /**
     * @var DynamicConfigurationManager
     */
    protected $dynamic_configuration_manager;

    /**
     * @param DynamicConfigurationManager $dynamic_configuration_manager
     * @return $this
     */
    public function setDynamicConfigurationManager(DynamicConfigurationManager $dynamic_configuration_manager)
    {
        $this->dynamic_configuration_manager = $dynamic_configuration_manager;
        return $this;
    }

    /**
     * @return DynamicConfigurationManager
     */
    public function getDynamicConfigurationManager()
    {
        return $this->dynamic_configuration_manager;
    }

    //-----------------------------------------------------------------------


    /**
     * @var UtilDynamicConfigurationManager
     */
    protected $util_dynamic_configuration_manager;

    /**
     * @return mixed
     */
    public function getUtilDynamicConfigurationManager()
    {
        return $this->util_dynamic_configuration_manager;
    }

    /**
     * @param mixed $util_dynamic_configuration_manager
     * @return DynamicConfigurationManager
     */
    public function setUtilDynamicConfigurationManager($util_dynamic_configuration_manager)
    {
        $this->util_dynamic_configuration_manager = $util_dynamic_configuration_manager;
        return $this;
    }



    //--------------------------------------------------------------------------------


    public function synchronizeOwnerConfigurationsFromAuthenticatedUser()
    {

        $em = $this->getDoctrineEntityManager();

        // TODO cambiar las consultas por las qb del manager
        $query = $em->createQuery('SELECT e_ FROM ' . $this->getDynamicConfigurationManager()->getResourceClassShortcut() . ' e_ '
            . 'WHERE e_.context = :context')
            ->setParameter('context', 'OWNER');

        $configurations = $query->getResult();

        $owner = $this->getResourceOwnerManager()->findOwnerOfAuthenticatedUser();

        foreach ($configurations as $cfg) {

            // TODO cambiar las consultas por los qb del manager
            $query_cfg = $em->createQuery('SELECT e_ FROM ' . $this->getResourceClassShortcut() . ' e_ '
                . 'JOIN e_.owner owner_ JOIN e_.configuration cfg WHERE owner_.id = :owner AND cfg.id = :configuration'
            )
                ->setParameter('owner', $owner->getId())
                ->setParameter('configuration', $cfg->getId());

            $cov = $query_cfg->getOneOrNullResult();

            if (is_null($cov)) {
                $cov = $this->createNewInstance();
                $cov->setName($cfg->getName() . '.' . $owner->getId());
                $cov->setTitle($cfg->getTitle() . '.' . $owner->getId());
                $cov->setConfiguration($cfg);
                $cov->setOwner($owner);
                $cov->setValue($cfg->getValue());
                //$cov->setMedia($cfg->getMedia());
                $this->prePersist($cov);
                $em->persist($cov);
                $em->flush($cov);
            }
        }

        return true;
    }


}
