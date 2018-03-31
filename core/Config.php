<?php

declare(strict_types=1);

namespace Petronetto;

use DirectoryIterator;

class Config
{
    /** @var string */
    private const CONFIG_PATH = __DIR__ . '/../config';

    /** @var array */
    private $data = [];

    /** @var Config */
    protected static $instance = null;

    protected function __construct()
    {
        $iterator = new DirectoryIterator(self::CONFIG_PATH);
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isDir()) {
                continue;
            }

            [$pathname, $filename] = $this->getPathAndFileName($fileInfo);

            $this->data[$filename] = require $pathname;
        }
    }

    private function getPathAndFileName(\SplFileInfo $fileInfo)
    {
        return [$fileInfo->getPathname(), trim($fileInfo->getFilename(), '.php')];
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

    public static function getInstance(): Config
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Prevent the clone method of this instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Prevent method be unserialized.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
