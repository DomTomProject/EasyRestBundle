<?php

namespace DomTomProject\EasyRestBundle\Parser;

use DomTomProject\EasyRestBundle\Provider\CacherProvider;
use DomTomProject\EasyRestBundle\Parser\CacherInterface;

class YamlRulesParser implements RulesParserInterface {

    /**
     * @var CacherInterface 
     */
    private $cacher;

    /**
     *
     * @var string 
     */
    private $validationPath;

    /**
     * 
     * @param CacherProvider $provider
     * @param string $validationPath
     */
    public function __construct(CacherProvider $provider, string $validationPath) {
        $this->cacher = $provider->provide();
        $this->validationPath = $validationPath;
    }

    /**
     * 
     * @return array
     */
    public function parse() {
        return ['parsowane' => 'tak'];
    }

}
