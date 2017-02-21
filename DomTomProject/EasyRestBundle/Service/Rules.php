<?php

namespace DomTomProject\EasyRestBundle\Service;

use DomTomProject\EasyRestBundle\Provider\RulesParserProvider;
use DomTomProject\EasyRestBundle\Parser\YamlRulesParser;
use DomTomProject\EasyRestBundle\Parser\RulesParserInterface;

/**
 * Getter for rules
 */
class Rules {

    /**
     * @var RulesParserInterface 
     */
    private $parser;

    /**
     * 
     * @param RulesParserProvider $provider
     */
    public function __construct(RulesParserProvider $provider) {
        $this->parser = $provider->provide();
    }

    /**
     * 
     * @param string $name
     * @return array
     */
    public function getDefault(string $name): array {
        return $this->parser->parse($name, 'default');
    }

    /**
     * 
     * @param string $name
     * @param string $key
     */
    public function get(string $name, string $key): array {
        return $this->parser->parse($name, $key);
    }

}
