<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Config;

/**
 * ConfigCache manages PHP cache files.
 *
 * When debug is enabled, it knows when to flush the cache
 * thanks to an array of ResourceInterface instances.
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class ConfigCache
{
    protected $debug;
    protected $cacheDir;
    protected $file;

    /**
     * Constructor.
     *
     * @param string  $cacheDir The cache directory
     * @param string  $file     The cache file name (without the .php extension)
     * @param Boolean $debug    Whether debugging is enabled or not
     */
    public function __construct($cacheDir, $file, $debug)
    {
        $this->cacheDir = $cacheDir;
        $this->file = $file;
        $this->debug = (Boolean) $debug;
    }

    /**
     * Gets the cache file path.
     *
     * @return string The cache file path
     */
    public function __toString()
    {
        return $this->getCacheFile();
    }

    /**
     * Checks if the cache is still fresh.
     *
     * This method always returns true is debug is on and the cache file exists.
     *
     * @return Boolean true if the cache is fresh, false otherwise
     */
    public function isFresh()
    {
        $file = $this->getCacheFile();
        if (!file_exists($file)) {
            return false;
        }

        if (!$this->debug) {
            return true;
        }

        $metadata = $this->getCacheFile('meta');
        if (!file_exists($metadata)) {
            return false;
        }

        $time = filemtime($file);
        $meta = unserialize(file_get_contents($metadata));
        foreach ($meta as $resource) {
            if (!$resource->isFresh($time)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Writes cache.
     *
     * @param string $content  The content to write in the cache
     * @param array  $metadata An array of ResourceInterface instances
     *
     * @throws \RuntimeException When cache file can't be wrote
     */
    public function write($content, array $metadata = null)
    {
        $file = $this->getCacheFile();
        $dir = dirname($file);
        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0777, true)) {
                throw new \RuntimeException(sprintf('Unable to create the %s directory', $dir));
            }
        } elseif (!is_writable($dir)) {
            throw new \RuntimeException(sprintf('Unable to write in the %s directory', $dir));
        }

        $tmpFile = tempnam(dirname($file), basename($file));
        if (false !== @file_put_contents($tmpFile, $content) && @rename($tmpFile, $file)) {
            chmod($file, 0666);
        } else {
            throw new \RuntimeException(sprintf('Failed to write cache file "%s".', $this->file));
        }

        if (null !== $metadata && true === $this->debug) {
            $file = $this->getCacheFile('meta');
            $tmpFile = tempnam(dirname($file), basename($file));
            if (false !== @file_put_contents($tmpFile, serialize($metadata)) && @rename($tmpFile, $file)) {
                chmod($file, 0666);
            }
        }
    }

    protected function getCacheFile($extension = 'php')
    {
        return $this->cacheDir.'/'.$this->file.'.'.$extension;
    }
}
