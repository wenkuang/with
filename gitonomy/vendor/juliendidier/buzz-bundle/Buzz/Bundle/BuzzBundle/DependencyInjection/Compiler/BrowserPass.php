<?php

namespace Buzz\Bundle\BuzzBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds all services with the tags "buzz.browser" as arguments of the
 * "buzz.browser_manager" service
 *
 * @author Julien DIDIER <julien@jdidier.net>
 */
class BrowserPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('buzz.browser_manager')) {
            return;
        }

        $bm = $container->getDefinition('buzz.browser_manager');

        foreach ($container->findTaggedServiceIds('buzz.browser') as $serviceId => $tag) {
            $name = isset($tag[0]['alias'])
                ? $tag[0]['alias']
                : $serviceId;


            $baseDefinition = $container->getDefinition('buzz.browser.'.$name);
            $definition = $container->getDefinition($serviceId);

            $arguments = $baseDefinition->getArguments();
            foreach ($arguments as $index => $argument) {
                $definition->replaceArgument($index, $argument);
            }

            $calls = $baseDefinition->getMethodCalls();
            foreach ($calls as $call) {
                $definition->addMethodCall($call[0], $call[1]);
            }

            $bm->addMethodCall('set', array($name, new Reference($serviceId)));
        }
    }
}
