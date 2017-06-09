<?php

namespace TechPromux\Bundle\ConfigurationBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\ConfigurationBundle\Entity\OwnerConfiguration;

/**
 * ConfigurationManager
 *
 */
class OwnerConfigurationManager extends BaseResourceManager {

    public function getBundleName() {
        return 'TechPromuxConfigurationBundle';
    }

    public function getMediaContextId()
    {
        return 'techpromux_configuration_media_image';
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
     * @var CustomConfigurationManager
     */
    protected $custom_configuration_manager;

    /**
     * @param CustomConfigurationManager $custom_configuration_manager
     * @return $this
     */
    public function setCustomConfigurationManager(CustomConfigurationManager $custom_configuration_manager)
    {
        $this->custom_configuration_manager = $custom_configuration_manager;
        return $this;
    }

    /**
     * @return CustomConfigurationManager
     */
    public function getCustomConfigurationManager()
    {
        return $this->custom_configuration_manager;
    }

    public function synchronizeOwnerConfigurationsFromAuthenticatedUser()
    {

        $em = $this->getDoctrineEntityManager();

        // TODO cambiar las consultas por las qb del manager
        $query = $em->createQuery('SELECT e_ FROM ' . $this->getCustomConfigurationManager()->getResourceClassShortcut() . ' e_ '
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
