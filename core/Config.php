<?php

declare(strict_types=1);

namespace Petronetto;

use DirectoryIterator;
use SplFileInfo;

class Config
{
    /** @var string */
    private const CONFIGS_DIR = __DIR__ . '/../config';

    /** @var array */
    private static $instance;

    /** @var array */
    private $data = [];

    /**
     * @param string $configsDir
     */
    protected function __construct(string $configsDir = null)
    {
        if (!$configsDir) {
            $configsDir = self::CONFIGS_DIR;
        }

        $iterator = new DirectoryIterator($configsDir);
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isDir()) {
                continue;
            }

            [$pathname, $filename] = $this->getPathAndFileName($fileInfo);

            $this->data[$filename] = require $pathname;
        }
    }

    /**
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param SplFileInfo $fileInfo
     * @return array
     */
    private function getPathAndFileName(SplFileInfo $fileInfo)
    {
        return [$fileInfo->getPathname(), substr($fileInfo->getFilename(), 0, -4)];
    }

    /**
     * Get the value of config.
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $parts = explode('.', $key);

        $pointer = $this->data;
        while ($part = array_shift($parts)) {
            if (!array_key_exists($part, $pointer)) {
                return $default;
            }

            $pointer = $pointer[$part];
        }

        return $pointer;
    }
}
