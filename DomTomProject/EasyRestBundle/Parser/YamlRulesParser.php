<?php

namespace DomTomProject\EasyRestBundle\Parser;

use Symfony\Component\Yaml\Yaml;
use DomTomProject\EasyRestBundle\Exception\RulesFileNotFoundException;
use DomTomProject\EasyRestBundle\Exception\RulesKeyNotFoundException;

/**
 * @author Damian Zschille <crunkowiec@gmail.com>
 */
class YamlRulesParser implements RulesParserInterface {

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
     * @param string $name
     * @param string $key
     * @return array
     * @throws RulesKeyNotFoundException
     */
    public function parse(string $name): array {
        $filename = $this->getFilename($name);
        $rules = Yaml::parse($this->getFile($name));

        return $this->getAllRules($rules);
    }

    /**
     * 
     * @return string
     */
    public function getType(): string {
        return 'yml';
    }

    /**
     * 
     * @param array $rules
     * @return array
     */
    public function getAllRules(array $rules): array {
        foreach ($rules as $key => $rule) {
            $rules[$key] = $this->generateRules($rule);
        }
        return $rules;
    }

    /**
     * Start converting 
     * 
     * @param array $rulesGroups
     * @return array
     */
    private function generateRules(array $rulesGroups): array {
        $rules = [];
        foreach ($rulesGroups as $key => $group) {
            $rules[$key] = $this->generateRuleForGroup($group);
        }

        $rules = $this->convertFromStringToPHP($rules);

        return $rules;
    }

    /**
     * Convert from 
     * 'name' => 'stringType()'
     * to
     * 'name' => v::stringType()
     * 
     * @param array $rules
     * @return array
     */
    private function convertFromStringToPHP(array $rules): array {

        foreach ($rules as $key => $rule) {
            $exported = var_export($rule, true);
            $exported = str_replace('\'', '', $exported);
            $exported = str_replace('\'v::', 'v::', $exported);
            $exported = str_replace(')\',', '),', $exported);
            $rules[$key] = 'v::' . $exported;
        }

        return $rules;
    }

    /**
     * Convert from
     * 'name' => [...]
     * to 
     * 'name' => stringType()
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
     * If function have arguments generate it here
     * 'name' => [ 'length' => [ 1, 128 ]]
     * to 
     * 'name' => 'length(1, 128)'
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

            $function .= $this->detectAndCreateArgument($argument);
        }

        $function = str_replace('$', '', $function);
        return $function . ')';
    }

    /**
     * If argument is array
     * 'name' => in(['a', 'b'])
     * 
     * @param array $arguments
     * @return string
     */
    private function generateArrayArgument(array $arguments): string {
        $arrayArgument = '[';
        $first = true;

        $isAssoc = $this->isAssoc($arguments);

        foreach ($arguments as $key => $argument) {
            if (!$first) {
                $arrayArgument .= ', ';
            }
            $first = false;

            if ($isAssoc) {
                $arrayArgument .= '"' . $key . '" => ';
            }

            $arrayArgument .= $this->detectAndCreateArgument($argument);
        }

        return $arrayArgument . ']';
    }

    /**
     * Detecting arugmnet type and create him
     * 
     * 
     * @param mixed $argument
     * @return string
     */
    private function detectAndCreateArgument($argument): string {
        $string = '';
        if (!is_array($argument)) {
            if (is_string($argument)) {
                $string .= '"' . $argument . '"';
                return $string;
            }
            $string .= $argument;
            return $string;
        } else {
            if ($this->isFunction($argument)) {
                $string .= 'v::' . $this->generateFunctionWithArguments($argument);
                return $string;
            }

            $string .= $this->generateArrayArgument($argument);
            return $string;
        }
        return $string;
    }

    /**
     * 
     * @param type $arguments
     * @return bool
     */
    private function isFunction($arguments): bool {
        return $this->isAssoc($arguments) && stristr(current(array_keys($arguments)), '$');
    }

    /**
     * Check is assoc array
     * @param array $array
     * @return bool
     */
    private function isAssoc(array $array): bool {
        if (array() === $array) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
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
