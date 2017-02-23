<?php

namespace DomTomProject\EasyRestBundle\Service;

use DomTomProject\EasyRestBundle\Provider\RulesParserProvider;
use DomTomProject\EasyRestBundle\Provider\CacherProvider;
use DomTomProject\EasyRestBundle\Parser\Cacher\CacherInterface;
use DomTomProject\EasyRestBundle\Parser\RulesParserInterface;
use DomTomProject\EasyRestBundle\Exception\RulesKeyNotFoundException;
use DomTomProject\EasyRestBundle\Exception\BadFilenameTypeException;


/**
 * Base class for getting rules
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class Rules {

    /**
     * @var RulesParserInterface 
     */
    private $parser;

    /**
     * @var CacherInterface 
     */
    private $cacher;

    /**
     * 
     * @param RulesParserProvider $provider
     */
    public function __construct(RulesParserProvider $parserProvider, CacherProvider $cacherProvider) {
        $this->parser = $parserProvider->provide();
        $this->cacher = $cacherProvider->provide();
    }

    /**
     * Use when you want to force use parser in some actions
     * @param RulesParserInterface $parser
     */
    public function setParser(RulesParserInterface $parser) {
        $this->parser = $parser;
    }

    /**
     * 
     * @param string $name
     * @return array
     */
    public function getDefault(string $name): array {
        return $this->get($name, 'default');
    }

    /**
     * 
     * @param string $name
     * @param string $key
     * @param RulesParserInterface $parser Use when you want use diffent parser in one action
     * @return array
     */
    public function get(string $name, string $key, RulesParserInterface $parser = null): array {
        $name = $this->detectFilename($name);
        
        if (empty($parser)) {
            $parser = $this->parser;
        }

        if ($parser->getType() === 'php') {
            $parsed = $parser->parse($name);

            if (!isset($parsed[$key])) {
                throw new RulesKeyNotFoundException('Key ' . $key . ' not found in ' . $name . ' rules file.');
            }
            return $parsed[$key];
        }

        $cached = $this->getCachedIfExists($name, $key);
        if (empty($cached) || !isset($cached[$key])) {

            $parsed = $parser->parse($name);

            if (!isset($parsed[$key])) {
                throw new RulesKeyNotFoundException('Key ' . $key . ' not found in ' . $name . ' rules file.');
            }

            return $this->cacher->save($name, $parsed)[$key];
        }

        return $cached[$key];
    }
    
    private function detectFilename(string $name): string{
        if(!is_string($name)){
            throw new BadFilenameTypeException('Filename must be a string');
        }
        $name = explode('\\', $name);
        
        return end($name);
    }

    /**
     * 
     * @param string $name
     * @param string $key
     * @return array
     */
    private function getCachedIfExists(string $name, string $key): array {
        if (!$this->cacher->isCached($name)) {
            return [];
        }

        $cached = $this->cacher->getCache($name);
        if (!isset($cached[$key])) {
            return [];
        }
        return $cached;
    }

}
