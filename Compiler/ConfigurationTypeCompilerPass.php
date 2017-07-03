<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ConfigurationTypeCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('techpromux_configuration.manager.custom_configuration')) {
            return;
        }

        $managerDefinition = $container->getDefinition(
            'techpromux_configuration.manager.custom_configuration'
        );

        $taggedServicesIds = $container->findTaggedServiceIds(
            'techpromux_configuration.type.configuration'
        );

        foreach ($taggedServicesIds as $id => $tags) {
            //$type = $container->getDefinition($id);
            $managerDefinition->addMethodCall(
                    'addCustomConfigurationType', array(new \Symfony\Component\DependencyInjection\Reference($id)));

        }
    }

}
