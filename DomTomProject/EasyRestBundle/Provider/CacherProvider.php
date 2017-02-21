<?php

namespace DomTomProject\EasyRestBundle\Provider;

use Symfony\Component\DependencyInjection\Container;
use ReflectionClass;
use DomTomProject\EasyRestBundle\Parser\CacherInterface;

class CacherProvider implements ProviderInterface {

    /**
     *
     * @var CacherInterface 
     */
    private $cacher;

    public function __construct(Container $container) {
        $cacher = $container->get($container->getParameter('domtom_rest.cacher_service'));
        
        if (!$this->isCacheImplementsInterface($cacher)) {
            throw new BadImplementationException('Cacher must implements ' . CacherInterface::class . '.');
        }

        $this->cacher = $cacher;
    }

    /**
     * 
     * @param mixed $cacher
     */
    private function isCacheImplementsInterface($cacher): bool {
        $reflection = new ReflectionClass($cacher);
        if (!$reflection->implementsInterface(CacherInterface::class)) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @return CacherInterface
     */
    public function provide() {
        return $this->cacher;
    }

}
