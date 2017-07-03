<?php

namespace DomTomProject\EasyRestBundle\Provider;

use DomTomProject\EasyRestBundle\Exception\BadImplementationException;
use Symfony\Component\DependencyInjection\Container;
use ReflectionClass;
use DomTomProject\EasyRestBundle\Parser\Cacher\CacherInterface;

class CacherProvider implements ProviderInterface {

    /**
     * @var CacherInterface
     */
    private $cacher;

    public function __construct(Container $container) {
        $cacher = $container->get($container->getParameter('domtom_easy_rest.cacher_service'));
        
        $this->isCacheImplementsInterface($cacher);

        $this->cacher = $cacher;
    }

    /**
     * @param mixed $cacher
     * @throws BadImplementationException
     */
    private function isCacheImplementsInterface($cacher) {
        $reflection = new ReflectionClass($cacher);

        if (!$reflection->implementsInterface(CacherInterface::class)) {
            throw new BadImplementationException('Cacher must implements ' . CacherInterface::class . '.');
        }
    }

    /**
     * @return CacherInterface
     */
    public function provide() {
        return $this->cacher;
    }

}
