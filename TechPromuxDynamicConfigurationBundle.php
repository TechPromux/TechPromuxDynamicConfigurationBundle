<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TechPromux\Bundle\DynamicConfigurationBundle\Compiler\ConfigurationTypeCompilerPass;

class TechPromuxDynamicConfigurationBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new ConfigurationTypeCompilerPass());
    }
}
