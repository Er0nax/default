<?php

namespace src\helpers;

use src\Config;

/**
 * Cache Helper
 */
class CacheHelper
{
    /**
     * Returns a hash of a given data or random php int.
     * @param mixed|null $data
     * @return string
     */
    public static function generateHash(mixed $data = null): string
    {
        if (empty($data)) {
            $data = rand(PHP_INT_MIN, PHP_INT_MAX);
        }

        return md5(serialize($data));
    }

    /**
     * Set data to the cache with a hash. It will return the hash.
     * @param mixed $data
     * @param string|null $hash
     * @return string|null
     */
    public static function setCache(mixed $data, string $hash = null, int $duration = null): ?string
    {
        // generate hash if it does not exist.
        if (empty($hash)) {
            $hash = self::generateHash($data);
        }

        // set default duration from config
        if (!isset($duration) || !is_numeric($duration)) {
            $dataType = self::getType($data);
            $duration = Config::getConfig('cacheDurations')[$dataType] ?? 0;
        }

        // set max alive time
        $maxTime = time() + $duration;

        // set data to session
        $_SESSION['cache'][$hash]['maxTime'] = $maxTime;
        $_SESSION['cache'][$hash]['data'] = $data;

        // return the hash value
        return $hash;
    }

    /**
     * Returns the hashed value or null if not found.
     * @param mixed $data
     * @param string|null $hash
     * @return mixed|null
     */
    public static function getCache(mixed $data, string $hash = null): mixed
    {
        // hash not given? => generate by data
        if (empty($hash)) {
            $hash = self::generateHash($data);
        }

        // check if hash value exists in session
        if (isset($_SESSION['cache'][$hash]['data'])) {

            // check if alive time is over
            $currentTime = time();
            $maxTime = $_SESSION['cache'][$hash]['maxTime'] ?? $currentTime;

            // below or equal 0?
            if ($maxTime - $currentTime <= 0) {
                return null;
            }

            // return the data
            return $_SESSION['cache'][$hash]['data'];
        }

        return null;
    }

    /**
     * Returns boolean whether the cache could be cleared or not.
     * @param string|null $hash when null, whole cache will be cleared.
     * @return bool
     */
    public static function clearCache(string $hash = null): bool
    {
        // specific hash given?
        if (!empty($hash)) {

            // Does hash exist in cache?
            if (isset($_SESSION['cache'][$hash])) {

                // delete cache by hash
                unset($_SESSION['cache'][$hash]);

                return true;
            }

            // return false as given hash does not exist in cache
            return false;
        }

        // no hash specific, so clear whole cache.
        unset($_SESSION['cache']);

        return true;
    }

    /**
     * Returns string whether the given data is html, json or unknown.
     * @param mixed $data
     * @return string
     */
    private static function getType(mixed $data): string
    {
        // Check if the input is valid JSON
        json_decode($data);
        if (json_last_error() === JSON_ERROR_NONE) {
            return 'json';
        }

        // Check if the input contains HTML tags
        if (preg_match('/<[^<]+>/', $data) != 0) {
            return 'html';
        }

        // Default return if no matches
        return 'default';
    }
}