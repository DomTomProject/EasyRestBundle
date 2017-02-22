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

        $this->isParserImplementsInterface($parser);

        $this->parser = $parser;
    }

    /**
     *
     * @param mixed $parser
     * @throws BadImplementationException
     */
    private function isParserImplementsInterface($parser) {
        $reflection = new ReflectionClass($parser);
        if (!$reflection->implementsInterface(RulesParserInterface::class)) {
            throw new BadImplementationException('Parser must implements ' . RulesParserInterface::class . '.');
        }
    }

    /**
     * 
     * @return RulesParserInterface
     */
    public function provide() {
        return $this->parser;
    }

}
