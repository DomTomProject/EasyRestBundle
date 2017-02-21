<?php

namespace DomTomProject\EasyRestBundle\Parser;

/**
 * Interface for Rules Parser
 */
interface RulesParserInterface {
    /**
     * 
     * @param string $name
     * @param string $key
     */
    public function parse(string $name, string $key): array;
}
