<?php

declare(strict_types=1);

namespace Petronetto;

use DirectoryIterator;
use SplFileInfo;

class Config
{
    /** @var string */
    private const CONFIG_PATH = __DIR__ . '/../config';

    /** @var array */
    private $data = [];

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

    private function getPathAndFileName(SplFileInfo $fileInfo)
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
    public static function get($key, $default = null)
    {
        $parts = explode('.', $key);

        $pointer = (new self)->getData();
        while ($part = array_shift($parts)) {
            if (!array_key_exists($part, $pointer)) {
                return $default;
            }

            $pointer = $pointer[$part];
        }

        return $pointer;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
