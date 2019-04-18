<?php

namespace PhpDocBlockChecker\Config;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class ConfigProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessDefaultConfig()
    {
        $config = [
            '--config-file' => ''
        ];

        $definition = $this->getDefinition();
        $configParser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $configProcessor = new ConfigProcessor($configParser);
        $result = $configProcessor->processConfig();

        $this->assertInstanceOf(Config::class, $result);

        $this->assertEquals('./', $result->getDirectory());
        $this->assertEquals([], $result->getExclude());
    }

    public function testProcessConfig()
    {
        $config = [
            '--config-file' => '',
            '--directory' => 'test',
            '--exclude' => 'foo,bar,baz'
        ];

        $definition = $this->getDefinition();
        $configParser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $configProcessor = new ConfigProcessor($configParser);
        $result = $configProcessor->processConfig();

        $this->assertInstanceOf(Config::class, $result);

        $this->assertEquals('test/', $result->getDirectory());
        $this->assertEquals(['foo', 'bar', 'baz'], $result->getExclude());
    }

    private function getDefinition()
    {
        $definition = new InputDefinition();
        $definition->addOption(
            new InputOption(
                'exclude',
                'x',
                InputOption::VALUE_REQUIRED,
                'Files and directories to exclude.'
            )
        );
        $definition->addOption(
            new InputOption(
                'directory',
                'd',
                InputOption::VALUE_REQUIRED,
                'Directory to scan.',
                './'
            )
        );
        $definition->addOption(
            new InputOption(
                'skip-classes',
                null,
                InputOption::VALUE_NONE,
                'Don\'t check classes for docblocks.'
            )
        );
        $definition->addOption(
            new InputOption(
                'skip-methods',
                null,
                InputOption::VALUE_NONE,
                'Don\'t check methods for docblocks.'
            )
        );
        $definition->addOption(
            new InputOption(
                'skip-signatures',
                null,
                InputOption::VALUE_NONE,
                'Don\'t check docblocks against method signatures.'
            )
        );
        $definition->addOption(
            new InputOption(
                'only-signatures',
                null,
                InputOption::VALUE_NONE,
                'Ignore missing docblocks where method doesn\'t have parameters or return type.'
            )
        );
        $definition->addOption(
            new InputOption(
                'json',
                'j',
                InputOption::VALUE_NONE,
                'Output JSON instead of a log.'
            )
        );
        $definition->addOption(
            new InputOption(
                'files-per-line',
                'l',
                InputOption::VALUE_REQUIRED,
                'Number of files per line in progress',
                50
            )
        );
        $definition->addOption(
            new InputOption(
                'fail-on-warnings',
                'w',
                InputOption::VALUE_NONE,
                'Consider the check failed if any warnings are produced.'
            )
        );
        $definition->addOption(
            new InputOption(
                'info-only',
                'i',
                InputOption::VALUE_NONE,
                'Information-only mode, just show summary.'
            )
        );
        $definition->addOption(
            new InputOption(
                'from-stdin',
                null,
                InputOption::VALUE_NONE,
                'Use list of files from stdin (e.g. git diff)'
            )
        );
        $definition->addOption(
            new InputOption(
                'cache-file',
                null,
                InputOption::VALUE_REQUIRED,
                'Cache analysis of files based on filemtime.'
            )
        );
        $definition->addOption(
            new InputOption(
                'config-file',
                null,
                InputOption::VALUE_REQUIRED,
                'File to read doccheck config from in yml format'
            )
        );

        return $definition;
    }
}
