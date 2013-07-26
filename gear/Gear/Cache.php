<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @package Optional
 * @category Gear
 * @copyright (c) 2013, Ryan Wong
 */
class Gear_Cache {

    /**
     * Given a filename, retrieve file as a variable
     * @param string $fileName
     * @return array
     */
    public static function get($fileName) {
        $fileName = ROOT . DS . 'tmp' . DS . 'cache' . DS . $fileName;
        if (file_exists($fileName)) {
            $handle = fopen($fileName, 'rb');
            $variable = fread($handle, filesize($fileName));
            fclose($handle);
            return unserialize($variable);
        } else {
            return array();
        }
    }

    /**
     * Given filename and variable, save variable to file as serialize array
     * @param string $fileName
     * @param mixed $variable
     */
    public static function set($fileName, $variable) {
        $fileName = ROOT . DS . 'tmp' . DS . 'cache' . DS . $fileName;
        $handle = fopen($fileName, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
    }

    /**
     * Given filename, delete file from /tmp folder
     * @param string $fileName
     */
    public static function delete($fileName) {
        $fileName = ROOT . DS . 'tmp' . DS . 'cache' . DS . $fileName;
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    }

    /**
     * If file exist and cache time not up, return file content as string.
     * Else empty string.
     * @param string $fileNameBase
     * @param int $cacheTime seconds
     * @return string
     */
    public static function getFullfile($fileNameBase, $cacheTime = 0) {
        $fileName = ROOT . DS . 'tmp' . DS . 'cache' . DS . $fileNameBase;
        if (file_exists($fileName)) {
            $filemtime = @filemtime($fileName);  // returns FALSE if file does not exist            
            if ($cacheTime && (time() - $filemtime >= $cacheTime)) {
                self::delete($fileNameBase);
                return '';
            }
            $handle = fopen($fileName, 'rb');
            $variable = fread($handle, filesize($fileName));
            fclose($handle);
            return $variable;
        } else {
            return '';
        }
    }

    /**
     * Given Filename and content, save to file as string
     * @param string $fileNameBase
     * @param string $content
     */
    public static function setFullFile($fileNameBase, $content) {
        $fileName = ROOT . DS . 'tmp' . DS . 'cache' . DS . $fileNameBase;
        $handle = fopen($fileName, 'a');
        fwrite($handle, $content);
        fclose($handle);
    }

    /**
     * Clear the cache.
     */
    public static function clearCache() {
        $path = ROOT . DS . 'tmp' . DS . 'cache';
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if (strlen($entry) < 5) {
                    continue;
                }
                self::delete($entry);
            }
        }
    }

}