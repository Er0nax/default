<?php

namespace src\helpers;

/**
 * FileHelper
 */
class FileHelper
{
    /**
     * Returns boolean whether a file exists or not.
     * @param string $path + BASE_PATH will be added before.
     * @return bool
     */
    public function exist(string $path): bool
    {
        return file_exists(BASE_PATH . $path);
    }

    /**
     * Returns the given path with BASE_PATH before.
     * @param string $path
     * @return string
     */
    public function get(string $path): string
    {
        return BASE_PATH . $path;
    }
}