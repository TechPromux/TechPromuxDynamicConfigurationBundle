<?php

namespace TechPromux\DynamicConfigurationBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TechPromux\DynamicConfigurationBundle\Compiler\VariableTypeCompilerPass;

class TechPromuxDynamicConfigurationBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new VariableTypeCompilerPass());
    }
}
