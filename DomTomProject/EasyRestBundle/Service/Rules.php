<?php

namespace DomTomProject\EasyRestBundle\Service;

use DomTomProject\EasyRestBundle\Provider\RulesParserProvider;
use DomTomProject\EasyRestBundle\Provider\CacherProvider;
use DomTomProject\EasyRestBundle\Parser\Cacher\CacherInterface;
use DomTomProject\EasyRestBundle\Parser\RulesParserInterface;

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
        $cached = $this->getCachedIfExists($name, $key);
        if (empty($cached)) {
            if (empty($parser)) {
                $parsed = $this->parser->parse($name, $key);
            } else {
                $parsed = $parser->parse($name, $key);
            }

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
