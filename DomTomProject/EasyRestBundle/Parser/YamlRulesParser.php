<?php

namespace DomTomProject\EasyRestBundle\Parser;

use Symfony\Component\Yaml\Yaml;
use DomTomProject\EasyRestBundle\Exception\RulesFileNotFoundException;
use DomTomProject\EasyRestBundle\Exception\RulesKeyNotFoundException;

class YamlRulesParser implements RulesParserInterface {

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
    public function __construct(string $validationPath) {
        $this->validationPath = $validationPath;
    }

    /**
     * @param string $name
     * @param string $key
     * @return array
     * @throws RulesKeyNotFoundException
     */
    public function parse(string $name, string $key): array {
        $filename = $this->getFilename($name);
        $rules = Yaml::parse($this->getFile($name));

        if (!isset($rules[$key])) {
            throw new RulesKeyNotFoundException('Key ' . $key . ' not found in ' . $name . ' rules file.');
        }

        //// tutaj będzie się działa magia
        dump($this->generateRules($rules[$key]));


        die();

        return $rules;
    }

    /**
     * 
     * @param array $rulesGroups
     */
    private function generateRules(array $rulesGroups): array {
        $rules = [];
        foreach ($rulesGroups as $key => $group) {
            $rules[$key] = $this->generateRuleForGroup($group);
        }

        return $rules;
    }

    /**
     * 
     * @param array $group
     * @return string
     */
    private function generateRuleForGroup(array $group): string {
        $first = true;
        $generated = '';

        foreach ($group as $rules) {
            if (!$first) {
                $generated .= '->';
            }
            $first = false;

            // method with no arguments, ex: stringType
            if (!is_array($rules)) {
                $generated .= $rules . '()';
                continue;
            }

            $generated .= $this->generateFunctionWithArguments($rules);
        }

        return $generated;
    }

    /**
     * 
     * @param string $functionName
     * @param array $arguments
     */
    private function generateFunctionWithArguments(array $arguments): string {
        // get function name
        $functionName = current(array_keys($arguments));
        $function = $functionName . '(';
        $first = true;

        // build args for this functions
        foreach ($arguments[$functionName] as $argument) {
            if (!$first) {
                $function .= ', ';
            }
            $first = false;

            if (!is_array($argument)) {
                $function .= $argument;
            }
        }

        return $function . ')';
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
        return $this->validationPath . '/' . $name . '.yml';
    }

}
