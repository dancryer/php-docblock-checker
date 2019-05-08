<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Config;

use Symfony\Component\Console\Input\InputDefinition;

/**
 * Class ConfigProcessor
 * @package PhpDocBlockChecker\Config
 */
class ConfigProcessor
{
    /**
     * @var ConfigParser
     */
    private $configParser;
    /**
     * @var InputDefinition
     */
    private $definition;

    /**
     * ConfigProcessor constructor.
     * @param ConfigParser $configParser
     * @param InputDefinition $definition
     */
    public function __construct(ConfigParser $configParser, InputDefinition $definition)
    {
        $this->configParser = $configParser;
        $this->definition = $definition;
    }

    /**
     * @return Config
     */
    public function processConfig(): Config
    {
        $config = [];

        foreach ($this->definition->getOptions() as $option) {
            $name = $option->getName();

            $config[$name] = $option->isValueRequired() || $option->isValueOptional() ?
                $this->configParser->parseParameter($name) :
                $this->configParser->parseOption($name);
        }

        return Config::fromArray($config);
    }
}
