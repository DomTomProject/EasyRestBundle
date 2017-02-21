<?php

namespace DomTomProject\EasyRestBundle\Service;

use DomTomProject\EasyRestBundle\Provider\RulesParserProvider;
use DomTomProject\EasyRestBundle\Provider\CacherProvider;
use DomTomProject\EasyRestBundle\Parser\Cacher\CacherInterface;
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
     * @var CacherInterface 
     */
    private $cacher;

    /**
     * 
     * @param RulesParserProvider $provider
     */
    public function __construct(RulesParserProvider $parser, CacherProvider $cacher) {
        $this->parser = $parser->provide();
        $this->cacher = $cacher->provide();
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
     */
    public function get(string $name, string $key): array {
        $cached = $this->getCachedIfExists($name, $key);

        if (empty($cached)) {
            $parsed = $this->parser->parse($name, $key);
            $this->cacher->save($name, $parsed);
            return $parsed;
        }

        return $cached;
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
