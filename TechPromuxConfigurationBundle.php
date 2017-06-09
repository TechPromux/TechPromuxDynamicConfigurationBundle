<?php

namespace TechPromux\Bundle\ConfigurationBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TechPromux\Bundle\ConfigurationBundle\Compiler\ConfigurationTypeCompilerPass;

class TechPromuxConfigurationBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new ConfigurationTypeCompilerPass());
    }
}
