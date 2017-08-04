<?php

namespace TechPromux\DynamicConfigurationBundle\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class VariableTypeCompilerPass implements CompilerPassInterface
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
            'techpromux_dynamic_configuration.type.variable'
        );

        foreach ($taggedServicesIds as $id => $tags) {

            $managerDefinition->addMethodCall(
                'addVariableType', array(new \Symfony\Component\DependencyInjection\Reference($id)));

        }
    }

}
