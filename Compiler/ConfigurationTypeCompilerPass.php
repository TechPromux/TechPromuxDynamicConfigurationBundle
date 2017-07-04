<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ConfigurationTypeCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('techpromux_dynamic_configuration.manager.util_dynamic_configuration')) {
            return;
        }

        $managerDefinition = $container->getDefinition(
            'techpromux_dynamic_configuration.manager.util_dynamic_configuration'
        );

        $taggedServicesIds = $container->findTaggedServiceIds(
            'techpromux_dynamic_configuration.type.configuration'
        );

        foreach ($taggedServicesIds as $id => $tags) {
            //$type = $container->getDefinition($id);
            $managerDefinition->addMethodCall(
                    'addDynamicConfigurationType', array(new \Symfony\Component\DependencyInjection\Reference($id)));

        }
    }

}
