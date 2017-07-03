<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use TechPromux\Bundle\DynamicConfigurationBundle\Entity\CustomConfiguration;
use TechPromux\Bundle\DynamicConfigurationBundle\Manager\CustomConfigurationManager;
use TechPromux\Bundle\DynamicConfigurationBundle\Type\BaseConfigurationType;
use TechPromux\Bundle\BaseBundle\Admin\Resource\BaseResourceAdmin;

class CustomConfigurationAdmin extends BaseResourceAdmin
{

    //protected $translationDomain = 'TechPromuxDynamicConfigurationBundle';


    /**
     *
     * @return string
     */
    public function getResourceManagerID()
    {
        return 'techpromux_configuration.manager.custom_configuration';
    }

    /**
     *
     * @return CustomConfigurationManager
     */
    public function getResourceManager()
    {
        return parent::getResourceManager();
    }

    /**
     *
     * @return CustomConfiguration
     */
    public function getSubject()
    {
        return parent::getSubject();
    }

    public function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $routes)
    {
        parent::configureRoutes($routes);

        // una sola ruta, indicar format json????

        $routes->remove('show');
        $routes->add('getValue', 'getValue/{code}');
        $routes->add('getJSONValue', 'getJSONValue/{code}');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            ->add('type')
            ->add('name')
            ->add('title')
            ->add('description')
            ->add('context')
            ->add('value');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('name')
            ->add('title')
            ->add('type')
            ->add('context')
            ->add('printableValue', 'html', array(
                //'label' => 'Value',
                'width' => '65',
                'height' => '65',
                'class' => 'img-polaroid'
            ));
        /*
          $listMapper->add('media', 'media_preview', array(
          'label' => 'Image',
          'width' => '45',
          'height' => '45',
          'class' => 'img-polaroid',
          )) ; */

        parent::configureListFields($listMapper);

        $listMapper->add('_action', 'actions', array(
            'label' => ('Actions'),
            'row_align' => 'right',
            'header_style' => 'width: 90px',
            'actions' => array(
                //'show' => array(),
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

        $this->getResourceManager()->transformValueToCustom($object);

        $formMapper
            ->with('form.group.definition.label', array('class' => 'col-md-4'))
            ->add('name', null, array())
            ->add('title', null, array())
            ->add('description', 'textarea', array())
            ->add('context', 'choice', array(
                'choices' => $this->getResourceManager()->getContextTypesChoices(),
                'multiple' => false, 'expanded' => false, 'required' => true
            ))
            ->end();

        $field_options_type = null;
        /** @var $field_options_type BaseConfigurationType */

        if ($this->getSubject() && $this->getSubject()->getId()) {

            $field_options_type = $this->getResourceManager()->getCustomConfigurationTypeById($object->getType());

        }

        if ($this->getSubject() && $this->getSubject()->getId()) {

            if ($field_options_type->getHasSettings()) {
                $formMapper->with('form.group.options.label', array('class' => 'col-md-4'))
                    ->add('settings', $field_options_type->getSettingsType(),
                        array_merge($field_options_type->getSettingsOptions(), array('required'=>false)))
                    ->end();
            }
        }

        if ($this->getSubject() && $this->getSubject()->getId() && $field_options_type->getHasSettings()) {
            $formMapper->with('form.group.value.label', array('class' => 'col-md-4'));
        } else {
            $formMapper->with('form.group.value.label', array('class' => 'col-md-8'));
        }

        $formMapper->add('type', ($this->getSubject() && $this->getSubject()->getId()) ? 'text' : 'choice', ($this->getSubject() && $this->getSubject()->getId()) ?
            array('disabled' => true) :
            array('multiple' => false, 'expanded' => true, 'required' => true, 'data' => 'text', 'disabled' => false,
                'choices' => $this->getResourceManager()->getCustomConfigurationTypesChoices())
        );

        if ($this->getSubject() && $this->getSubject()->getId()) {

            if (!$field_options_type->getHasSettings()) {
                $formMapper->add('customValue', $field_options_type->getValueType(),
                    array_merge($field_options_type->getValueOptions(), array('required'=>false))
                );
            } else {
                if ($object->getSettings()) {
                    $value_choices = $this->getResourceManager()->getSettingsOptionsChoices($object);
                    $formMapper
                        ->add('customValue', $field_options_type->getValueType(),
                            array_merge($field_options_type->getValueOptions(), array(
                                'required'=>false,
                                'choices' => $value_choices,
                            )));
                }
            }
        }

        $formMapper->end();


    }

    public function toString($object)
    {
        return $object->getName();
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'base_list_field':
                return 'TechPromuxDynamicConfigurationBundle:Admin:CRUD/base_list_field.html.twig';
        }
        return parent::getTemplate($name);
    }

    /**
     * @param CustomConfiguration $object
     */
    public function preUpdate($object)
    {
        parent::preUpdate($object); // TODO: Change the autogenerated stub

        $this->getResourceManager()->transformCustomToValue($object);

    }


}
