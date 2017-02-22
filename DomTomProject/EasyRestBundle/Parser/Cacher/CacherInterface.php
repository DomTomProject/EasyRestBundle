<?php

namespace DomTomProject\EasyRestBundle\Parser\Cacher;

/**
 *  @author Damian Zschille <crunkowiec@gmail.com>
 * 
 *  target is caching parsed validation rules
 */
interface CacherInterface {

    /**
     * Should return false if env is dev
     * 
     * @param string $file
     * @param bool $absolute
     */
    public function isCached(string $file, bool $absolute = false): bool;

    /**
     * 
     * @param string $file
     * @param bool $absolute
     */
    public function getCache(string $file, bool $absolute = false): array;

    /**
     * 
     * @param string $file
     * @param array $data
     * @param bool $absolute
     */
    public function save(string $file, array $data, bool $absolute = false): array;
}
