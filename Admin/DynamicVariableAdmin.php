<?php

namespace TechPromux\DynamicConfigurationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use TechPromux\DynamicConfigurationBundle\Entity\DynamicVariable;
use TechPromux\DynamicConfigurationBundle\Manager\DynamicVariableManager;
use TechPromux\DynamicConfigurationBundle\Type\Variable\BaseVariableType;
use TechPromux\BaseBundle\Admin\Resource\BaseResourceAdmin;

class DynamicVariableAdmin extends BaseResourceAdmin
{
    /**
     *
     * @return DynamicVariableManager
     */
    public function getResourceManager()
    {
        return parent::getResourceManager();
    }

    /**
     *
     * @return DynamicVariable
     */
    public function getSubject()
    {
        return parent::getSubject();
    }

    public function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $routes)
    {
        parent::configureRoutes($routes);
        $routes->remove('show');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        parent::configureDatagridFilters($datagridMapper);

        $datagridMapper
            ->add('type', null, array(), 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getVariableTypesChoices(),
                'translation_domain' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getBundleName()
            ))
            ->add('name')
            ->add('title')
            //->add('description')
            ->add('contextType', null, array(), 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getContextTypesChoices(),
                'translation_domain' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getBundleName()
            ))
            ->add('value');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('name')
            ->add('title')
            ->add('type', 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getVariableTypesChoices(true),
            ))
            ->add('contextType', 'choice', array(
                'choices' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getContextTypesChoices(true)
            ))
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

        $this->getResourceManager()->getUtilDynamicConfigurationManager()->transformValueToCustom($object);

        $formMapper
            ->with('form.group.definition.label', array('class' => 'col-md-4'))
            ->add('name', null, array())
            ->add('title', null, array())
            ->add('description', 'textarea', array())
            ->end();

        $field_options_type = null;
        /** @var $field_options_type BaseVariableType */

        if ($this->getSubject() && $this->getSubject()->getId()) {

            $field_options_type = $this->getResourceManager()->getUtilDynamicConfigurationManager()->getVariableTypeById($object->getType());

        }

        if ($this->getSubject() && $this->getSubject()->getId()) {
            $formMapper->with('form.group.options.label', array('class' => 'col-md-4'));
        } else {
            $formMapper->with('form.group.options.label', array('class' => 'col-md-8'));

        }
        $formMapper->add('contextType', 'choice', array(
            'choices' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getContextTypesChoices(),
            'multiple' => false, 'expanded' => false, 'required' => true,
            'translation_domain' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getBundleName()
        ));

        $formMapper->add('type', 'choice',
            array(
                'disabled' => ($this->getSubject() && $this->getSubject()->getId()) ? true : false,
                'multiple' => false,
                'expanded' => ($this->getSubject() && $this->getSubject()->getId()) ? false : true,
                'required' => true,
                'choices' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getVariableTypesChoices(),
                'translation_domain' => $this->getResourceManager()->getUtilDynamicConfigurationManager()->getBundleName()
            )
        );

        if ($this->getSubject() && $this->getSubject()->getId() && $field_options_type->getHasSettings()) {
            $formMapper->
            add('settings', $field_options_type->getSettingsType(),
                array_merge($field_options_type->getSettingsOptions(), array('required' => false)));

        }

        $formMapper->end();

        if ($this->getSubject() && $this->getSubject()->getId()) {
            $formMapper->with('form.group.value.label', array('class' => 'col-md-4'));

            if (!$field_options_type->getHasSettings()) {
                $formMapper->add('customValue', $field_options_type->getValueType(),
                    array_merge($field_options_type->getValueOptions(), array('required' => false))
                );
            } else {
                if ($object->getSettings()) {
                    $value_choices = $this->getResourceManager()->getUtilDynamicConfigurationManager()->getSettingsOptionsChoices($object);
                    $formMapper
                        ->add('customValue', $field_options_type->getValueType(),
                            array_merge($field_options_type->getValueOptions(), array(
                                'required' => false,
                                'choices' => $value_choices,
                            )));
                }
            }
            $formMapper->end();
        }


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
     * @param DynamicVariable $object
     */
    public function preUpdate($object)
    {
        parent::preUpdate($object); // TODO: Change the autogenerated stub

        $this->getResourceManager()->getUtilDynamicConfigurationManager()->transformCustomToValue($object);

    }


}
