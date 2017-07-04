<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\OwnerConfiguration;
use TechPromux\Bundle\DynamicConfigurationBundle\Manager\DynamicConfigurationManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Manager\OwnerConfigurationManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\Configuration\BaseConfigurationType;
use TechPromux\Bundle\BaseBundle\Admin\Resource\BaseResourceAdmin;

class OwnerConfigurationAdmin extends BaseResourceAdmin
{
    /**
     *
     * @return OwnerConfigurationManager
     */
    public function getResourceManager()
    {
        return parent::getResourceManager();
    }

    /**
     *
     * @return OwnerConfiguration
     */
    public function getSubject()
    {
        return parent::getSubject();
    }

    public function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('show');
        $collection->remove('create');
        $collection->remove('delete');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);
        $datagridMapper
            ->add('configuration.name', null, array())
            ->add('configuration.description', null, array())
            ->add('value');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $this->getResourceManager()->synchronizeOwnerConfigurationsFromAuthenticatedUser();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $listMapper->addIdentifier('configuration.name', null, array());
            $listMapper->add('configuration.title', null, array());
        } else {
            $listMapper->addIdentifier('configuration.title', null, array());
        }
        $listMapper->add('configuration.type', 'choice', array(
            'choices' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getDynamicConfigurationTypesChoices()
        ));
        $listMapper->add('printableValue', 'html', array(
            //'label' => 'Value',
            'width' => '65',
            'height' => '65',
            'class' => 'img-polaroid'
        ));

        parent::configureListFields($listMapper);

        $listMapper->add('_action', 'actions', array(
            'label' => ('Actions'),
            'row_align' => 'right',
            'header_style' => 'width: 90px',
            'actions' => array(
                'show' => array(),
                'edit' => array(),
                'delete' => array(),
            )
        ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        parent::configureFormFields($formMapper);

        $object = $this->getSubject();

        $object = $this->getSubject();

        $this->getResourceManager()->getUtilDynamicConfigurationManager()->transformValueToCustom($object);

        $formMapper
            ->with('Var Definition', array('class' => 'col-md-4'));

        $formMapper->add('configuration.description', null, array('label' => 'Description',
            //'mapped' => false,
            'disabled' => true,
        ))
            ->add('reset_value', 'sonata_type_choice_field_mask', array('label' => 'Custom Value or Reset Value to Default',
                //'empty_data' => '0',
                'mapped' => false,
                'choices' => array(
                    'owner-configuration-keep-custom-value' => 'owner-configuration-keep-custom-value',
                    'owner-configuration-reset-to-default' => 'owner-configuration-reset-to-default',
                ),
                'map' => array(
                    'owner-configuration-keep-custom-value' => array('customValue'),
                    'owner-configuration-reset-to-default' => array(),
                ),
                //'empty_value' => 'Choose an option',
                'required' => true,
            ))
            ->end();

        $formMapper->with('Value Options', array('class' => 'col-md-8'));

        $field_options_type = $this->getResourceManager()->getUtilDynamicConfigurationManager()->getDynamicConfigurationTypeById($object->getConfiguration()->getType());
        /** @var $field_options_type BaseConfigurationType */

        if (!$field_options_type->getHasSettings()) {
            $formMapper->add('customValue', $field_options_type->getValueType(),
                array_merge($field_options_type->getValueOptions(), array(
                    'required' => false,
                ))
            );
        } else {
            if ($object->getConfiguration()->getSettings()) {
                $value_choices = $this->getResourceManager()->getUtilDynamicConfigurationManager()->getSettingsOptionsChoices($object->getConfiguration());
                $formMapper
                    ->add('customValue', $field_options_type->getValueType(),
                        array_merge($field_options_type->getValueOptions(), array(
                            'choices' => $value_choices,
                            'required' => false,
                        )));
            }
        }
        $formMapper->end();
    }

    public function preUpdate($object)
    {

        $request = $this->getRequest();
        $formId = $request->get('uniqid');
        $formData = $request->get($formId);

        if ('owner-configuration-reset-to-default' == $formData['reset_value']) {
            $object->setValue($object->getConfiguration()->getValue());
            $object->setMedia($object->getConfiguration()->getMedia());
        }

        parent::preUpdate($object);

        $this->getResourceManager()->getUtilDynamicConfigurationManager()->transformCustomToValue($object);

    }

    public function toString($object)
    {
        return $object->getConfiguration()->getName();
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'base_list_field':
                return 'TechPromuxDynamicConfigurationBundle:Admin:CRUD/base_list_field.html.twig';
        }
        return parent::getTemplate($name);
    }

}
