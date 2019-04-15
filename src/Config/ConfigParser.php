<?php

namespace PhpDocBlockChecker\Config;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigParser
 * @package PhpDocBlockChecker\Config
 */
class ConfigParser
{
    const DEFAULT_CONFIG_FILE = 'phpdoccheck.yml';
    /**
     * @var InputInterface
     */
    private $input;
    /**
     * @var array
     */
    private $fileConfig;
    private $definition;

    /**
     * OptionProcessor constructor.
     * @param InputInterface $input
     * @param InputDefinition $definition
     */
    public function __construct(InputInterface $input, InputDefinition $definition)
    {
        $this->input = $input;
        $this->definition = $definition;

        $inputConfigFile = $input->getOption('config-file');

        if (is_array($inputConfigFile)) {
            $inputConfigFile = reset($inputConfigFile);
        }

        $configFile = $inputConfigFile !== null ?
            (string)$inputConfigFile :
            getcwd() . '/' . self::DEFAULT_CONFIG_FILE;

        $this->fileConfig = $this->parseConfigFile($configFile);
    }

    /**
     * If the option is set in either the command line or config file, return true
     *
     * @param string $optionName
     * @return bool
     */
    public function parseOption($optionName)
    {
        return $this->input->getOption($optionName) || isset($this->fileConfig['options'][$optionName]);
    }

    /**
     * @param string $parameterName
     * @return mixed
     */
    public function parseParameter($parameterName)
    {
        $defaultValue = $this->definition->getOption($parameterName)->getDefault();
        $inputValue = $this->input->getOption($parameterName);

        if ($inputValue !== $defaultValue) {
            return $inputValue;
        }

        $fileValue = $this->getFileValue($parameterName);

        if ($fileValue !== null) {
            return $fileValue;
        }

        return $defaultValue;
    }

    /**
     * @param string $parameterName
     * @return mixed|null
     */
    private function getFileValue($parameterName)
    {
        if (isset($this->fileConfig[$parameterName])) {
            return $this->fileConfig[$parameterName];
        }
        return null;
    }

    /**
     * @param string $configFile
     * @return array
     */
    private function parseConfigFile($configFile)
    {
        if (!file_exists($configFile)) {
            return ['options' => []];
        }

        $config = Yaml::parseFile($configFile);

        if (isset($config['options'])) {
            $config['options'] = array_flip($config['options']);
        }

        return $config;
    }
}
