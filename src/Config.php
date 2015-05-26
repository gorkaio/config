<?php

namespace Gorka\Config;

use Gorka\Config\Exception\ConfigNotFoundException;
use Gorka\Config\Exception\InvalidConfigException;
use Gorka\DotNotationAccess\DotNotationAccessArray;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 * @package Gorka\Config
 */
class Config {

    /**
     * @var DotNotationAccessArray
     */
    protected $config;

    /**
     * @param $configFileName
     * @throws ConfigNotFoundException
     * @throws InvalidConfigException
     */
    public function __construct($configFileName)
    {
        if (!file_exists($configFileName) || !is_readable($configFileName)) {
            throw new ConfigNotFoundException();
        }

        try {
            $data = file_get_contents($configFileName);
            $this->config = new DotNotationAccessArray(Yaml::parse($data));
        } catch (\Exception $e) {
            throw new InvalidConfigException("Unable to parse config: ".$e->getMessage());
        }
    }

    /**
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function get($param, $default = null)
    {
        return $this->config->get($param, $default);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->config->getAll();
    }

    /**
     * @param $path
     * @return bool
     */
    public function has($path)
    {
        return $this->config->has($path);
    }
}