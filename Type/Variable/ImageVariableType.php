<?php
/**
 * Created by PhpStorm.
 * User: franklin
 * Date: 17/05/2017
 * Time: 19:11
 */

namespace  TechPromux\DynamicConfigurationBundle\Type\Variable;


use  TechPromux\DynamicConfigurationBundle\Manager\DynamicVariableManager;

class ImageVariableType implements BaseVariableType
{

    public function getId()
    {
        return 'image';
    }

    public function getTitle()
    {
        return 'image';
    }

    public function getValueType()
    {
        return 'sonata_media_type';
    }

    public function getValueOptions()
    {
        return array(
            'provider' => 'sonata.media.provider.image',
            'context' => $this->getMediaContext()->getId(),
            'attr' => array(
                'class' => 'type_media_image'
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
        $value = $object->getMedia();
        $custom = $value;
        $object->setCustomValue($custom);
        return $object;
    }

    public function transformCustomToValue($object)
    {
        $custom = $object->getCustomValue();
        $value = $custom;

        if (!is_null($value)) {
            $provider = $this->service_container->get($value->getProviderName());

            $url = $provider->generatePublicUrl($value, 'reference');

            $object->setValue(json_encode($url));
        } else {
            $object->setValue(null);
        }

        $object->setMedia($value);
        return $object;
    }

    //-------------------------------------------------------------------


    private $service_container;



    /**
     * @param $service_container
     * @return $this
     */
    public function setServiceContainer($service_container)
    {
        $this->service_container = $service_container;
        return $this;
    }

    //------------------------------------------------------------------

    /**
     * @var EntityManagerInterface
     */
    private $entity_manager;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entity_manager;
    }

    /**
     * @param EntityManagerInterface $entity_manager
     * @return BaseManager
     */
    public function setEntityManager($entity_manager)
    {
        $this->entity_manager = $entity_manager;
        return $this;
    }

    //-------------------------------------------------------------------

    /**
     * Obtiene el servicio "sonata.classification.manager.context"
     *
     * @return \Sonata\ClassificationBundle\Entity\ContextManager
     */
    protected function getClassificationContextManager()
    {
        return $this->service_container->get('sonata.classification.manager.context');
    }

    /**
     * Obtiene el servicio "sonata.classification.manager.category"
     *
     * @return \Sonata\ClassificationBundle\Entity\CategoryManager
     */
    protected function getClassificationCategoryManager()
    {
        return $this->service_container->get('sonata.classification.manager.category');
    }

    /**
     * Obtiene el servicio "sonata.media.manager.media"
     *
     * @return \Sonata\MediaBundle\Entity\MediaManager
     */
    protected function getMediaManager()
    {
        return $this->service_container->get('sonata.media.manager.media');
    }

    /**
     * Obtiene el ID utilizado por defecto para generar contextos de medias
     *
     * @return string
     */
    protected function getDefaultMediaContextId()
    {
        return 'techpromux_dynamic_configuration_media_image';
    }

    /**
     * Obtiene el ID utilizado para generar contextos de medias
     *
     * @return string
     */
    public function getMediaContextId()
    {
        return $this->getDefaultMediaContextId();
    }

    /**
     * Obtiene el contexto de medias
     *
     * @return \Sonata\ClassificationBundle\Entity\BaseContext
     */
    public function getMediaContext($context_id = null)
    {

        if (is_null($context_id)) {
            $context_id = $this->getMediaContextId();
        }

        $context = $this->getClassificationContextManager()->find($context_id);

        if (is_null($context)) {
            $context = $this->getClassificationContextManager()->create();
            $context->setId($context_id);
            $context->setName($context_id);
            $context->setEnabled(true);
            $context->setCreatedAt(new \Datetime());
            $context->setUpdatedAt(new \Datetime());
            $this->getEntityManager()->persist($context);
            $this->getEntityManager()->flush($context);

            $defaultCategory = $this->getClassificationCategoryManager()->create();
            $defaultCategory->setContext($context);
            $defaultCategory->setName($context->getId() . '_default');
            $defaultCategory->setEnabled(true);
            $defaultCategory->setCreatedAt(new \Datetime());
            $defaultCategory->setUpdatedAt(new \Datetime());
            $defaultCategory->setSlug($context->getId() . '_default');
            $defaultCategory->setDescription($context->getId() . '_default');
            $this->getEntityManager()->persist($defaultCategory);
            $this->getEntityManager()->flush($defaultCategory);
        }

        return $context;
    }

}