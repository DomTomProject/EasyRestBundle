<?php

namespace DomTomProject\EasyRestBundle\Parser;

use DomTomProject\EasyRestBundle\Exception\RulesFileNotFoundException;
use DomTomProject\EasyRestBundle\Exception\RulesKeyNotFoundException;

/**
 * @author Damian Zschille <crunkowiec@gmail.com>
 */
class PHPRulesParser implements RulesParserInterface {

    /**
     * Place where rules exists ,default its app/Resources/validation
     * @var string 
     */
    private $validationPath;

    /**
     * 
     * @param string $validationPath
     */
    public function __construct(string $validationPath) {
        $this->validationPath = $validationPath;
    }

    /**
     * 
     * @param string $name
     * @param string $key
     * @return array
     * @throws RulesKeyNotFoundException
     */
    public function parse(string $name, string $key): array {
        $rules = require $this->getFile($name);

        if (!isset($rules[$key])) {
            throw new RulesKeyNotFoundException('Key ' . $key . ' not found in ' . $name . ' rules file.');
        }

        return $rules[$key];
    }

    /**
     * 
     * @param string $name
     * @return string
     * @throws RulesFileNotFoundException
     */
    private function getFile(string $name): string {
        $filename = $this->getFilename($name);
        if (!file_exists($filename)) {
            throw new RulesFileNotFoundException('Validation file not found in: ' . $filename);
        }

        return file_get_contents($filename);
    }

    /**
     * @param string $name
     * @return string
     */
    private function getFilename(string $name): string {
        return $this->validationPath . '/' . $name . '.php';
    }

}
