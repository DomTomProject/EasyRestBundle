<?php

namespace DomTomProject\EasyRestBundle\Parser;

/**
 *  @author Damian Zschille <crunkowiec@gmail.com>
 * Interface for Rules Parser
 * Target is convert parsed data to something like this:
 * 
 * array(
 *   'name' => v::stringType(),
 *   'age'  => v::intVal(),
 *   'hello_in_two_languages' => v::keySet(v::key('pl'), v::key('en')),
 * )
 */
interface RulesParserInterface {
    /**
     * 
     * @param string $name
     * @param string $key
     */
    public function parse(string $name): array;
    
    /**
     * Get type of format. Example: php, yml . If parser have PHP type data is not cached
     */
    public function getType(): string;
}
