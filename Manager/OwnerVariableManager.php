<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Manager;

use TechPromux\Bundle\BaseBundle\Manager\Resource\BaseResourceManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerVariable;

/**
 * VariableManager
 *
 */
class OwnerVariableManager extends BaseResourceManager {

    public function getBundleName() {
        return 'TechPromuxDynamicConfigurationBundle';
    }

    public function getMediaContextId()
    {
        return 'techpromux_dynamic_configuration_media_image';
    }

    /**
     * Get entity class name
     *
     * @return class
     */
    public function getResourceClass()
    {
        return OwnerVariable::class;
    }

    /**
     * Get entity short name
     *
     * @return string
     */
    public function getResourceName()
    {
        return 'OwnerVariable';
    }

    //------------------------------------------

    /**
     * @var DynamicVariableManager
     */
    protected $dynamic_configuration_manager;

    /**
     * @param DynamicVariableManager $dynamic_configuration_manager
     * @return $this
     */
    public function setDynamicVariableManager(DynamicVariableManager $dynamic_configuration_manager)
    {
        $this->dynamic_configuration_manager = $dynamic_configuration_manager;
        return $this;
    }

    /**
     * @return DynamicVariableManager
     */
    public function getDynamicVariableManager()
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
     * @return DynamicVariableManager
     */
    public function setUtilDynamicConfigurationManager($util_dynamic_configuration_manager)
    {
        $this->util_dynamic_configuration_manager = $util_dynamic_configuration_manager;
        return $this;
    }



    //--------------------------------------------------------------------------------


    public function synchronizeOwnerVariablesFromAuthenticatedUser()
    {

        $em = $this->getDoctrineEntityManager();

        // TODO cambiar las consultas por las qb del manager
        $query = $em->createQuery('SELECT e_ FROM ' . $this->getDynamicVariableManager()->getResourceClassShortcut() . ' e_ '
            . 'WHERE e_.context = :context')
            ->setParameter('context', 'OWNER');

        $variables = $query->getResult();

        $owner = $this->getResourceOwnerManager()->findOwnerOfAuthenticatedUser();

        foreach ($variables as $cfg) {

            // TODO cambiar las consultas por los qb del manager
            $query_cfg = $em->createQuery('SELECT e_ FROM ' . $this->getResourceClassShortcut() . ' e_ '
                . 'JOIN e_.owner owner_ JOIN e_.variable cfg WHERE owner_.id = :owner AND cfg.id = :variable'
            )
                ->setParameter('owner', $owner->getId())
                ->setParameter('variable', $cfg->getId());

            $cov = $query_cfg->getOneOrNullResult();

            if (is_null($cov)) {
                $cov = $this->createNewInstance();
                $cov->setName($cfg->getName() . '.' . $owner->getId());
                $cov->setTitle($cfg->getTitle() . '.' . $owner->getId());
                $cov->setVariable($cfg);
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
