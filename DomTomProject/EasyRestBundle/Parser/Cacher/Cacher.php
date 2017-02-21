<?php

namespace DomTomProject\EasyRestBundle\Parser\Cacher;

class Cacher implements CacherInterface {

    /**
     *
     * @var string 
     */
    private $env;

    /**
     *
     * @var string
     */
    private $cacheDir;

    /**
     * 
     * @param string $env
     * @param string $cacheDir
     */
    public function __construct(string $env, string $cacheDir) {
        $this->env = $env;
        $this->cacheDir = $cacheDir;
    }

    /**
     * 
     * @param string $file
     * @param bool $absolute
     * @return array
     */
    public function getCache(string $file, bool $absolute = false): array {
        $filename = $this->cacheDir . '/validation/' . $file . '.php';
        if (!file_exists($filename)) {
            return [];
        }

        return require $filename;
    }

    /**
     * 
     * @param string $file
     * @param bool $absolute
     * @return bool
     */
    public function isCached(string $file, bool $absolute = false): bool {
        if ($this->env === 'dev') {
            return false;
        }

        if (file_exists($this->cacheDir . '/validation/' . $file . '.php')) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param string $file
     * @param array $data
     * @param bool $absolute
     */
    public function save(string $file, array $data, bool $absolute = false) {
        
    }

}
