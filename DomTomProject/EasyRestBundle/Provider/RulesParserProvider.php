<?php

namespace DomTomProject\EasyRestBundle\Provider;

use Symfony\Component\DependencyInjection\Container;
use DomTomProject\EasyRestBundle\Parser\RulesParserInterface;
use DomTomProject\EasyRestBundle\Exception\BadImplementationException;
use ReflectionClass;

class RulesParserProvider implements ProviderInterface {

    /**
     * @var RulesParserInterface 
     */
    private $parser;

    /**
     * 
     * @param Container $container
     */
    public function __construct(Container $container) {
        $parser = $container->get($container->getParameter('domtom_easy_rest.rules_parser_service'));

        if (!$this->isParserImplementsInterface($parser)) {
            throw new BadImplementationException('Parser must implements ' . RulesParserInterface::class . '.');
        }

        $this->parser = $parser;
    }

    /**
     * 
     * @param mixed $parser
     */
    private function isParserImplementsInterface($parser): bool {
        $reflection = new ReflectionClass($parser);
        if (!$reflection->implementsInterface(RulesParserInterface::class)) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @return RulesParserInterface
     */
    public function provide() {
        return $this->parser;
    }

}
