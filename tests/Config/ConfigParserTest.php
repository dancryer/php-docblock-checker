<?php

namespace PhpDocBlockChecker\Config;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class ConfigParserTest extends \PHPUnit_Framework_TestCase
{

    public function testParseOptionDefaultValue()
    {
        $config = [
            '--config-file' => ''
        ];
        $definition = $this->getDefinition();
        $parser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $result = $parser->parseOption('skip-classes');

        $this->assertFalse($result);
    }

    public function testParseOptionFileValue()
    {
        $tmpfile = tempnam(sys_get_temp_dir(), 'phpdoccheck');

        file_put_contents($tmpfile, "options:\n  - skip-classes");
        $config = [
            '--config-file' => $tmpfile
        ];
        $definition = $this->getDefinition();
        $parser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $result = $parser->parseOption('skip-classes');

        $this->assertTrue($result);

        unlink($tmpfile);
    }

    public function testParseOptionParamValue()
    {
        $config = [
            '--config-file' => '',
            '--skip-classes' => null,
        ];
        $definition = $this->getDefinition();
        $parser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $result = $parser->parseOption('skip-classes');

        $this->assertTrue($result);
    }

    public function testParseOptionFileAndParamValue()
    {
        $tmpfile = tempnam(sys_get_temp_dir(), 'phpdoccheck');

        file_put_contents($tmpfile, "options:\n  - skip-classes");
        $config = [
            '--config-file' => $tmpfile,
            '--skip-classes' => null,
        ];
        $definition = $this->getDefinition();
        $parser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $result = $parser->parseOption('skip-classes');

        $this->assertTrue($result);

        unlink($tmpfile);
    }


    public function testParseParameterDefaultValue()
    {
        $config = [
            '--config-file' => ''
        ];
        $definition = $this->getDefinition();
        $parser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $result = $parser->parseParameter('directory');

        $this->assertEquals('./', $result);
    }

    public function testParseParameterFileValue()
    {
        $tmpfile = tempnam(sys_get_temp_dir(), 'phpdoccheck');

        file_put_contents($tmpfile, 'directory: fileTest');
        $config = [
            '--config-file' => $tmpfile
        ];
        $definition = $this->getDefinition();
        $parser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $result = $parser->parseParameter('directory');

        $this->assertEquals('fileTest', $result);

        unlink($tmpfile);
    }

    public function testParseParameterParamValue()
    {
        $config = [
            '--config-file' => '',
            '--directory' => 'paramTest',
        ];
        $definition = $this->getDefinition();
        $parser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $result = $parser->parseParameter('directory');

        $this->assertEquals('paramTest', $result);
    }

    public function testParseParameterFileAndParamValue()
    {
        $tmpfile = tempnam(sys_get_temp_dir(), 'phpdoccheck');

        file_put_contents($tmpfile, 'directory: fileTest');
        $config = [
            '--config-file' => $tmpfile,
            '--directory' => 'paramTest',
        ];
        $definition = $this->getDefinition();
        $parser = new ConfigParser(new ArrayInput($config, $definition), $definition);
        $result = $parser->parseParameter('directory');

        $this->assertEquals('paramTest', $result);
        unlink($tmpfile);
    }

    private function getDefinition()
    {
        $definition = new InputDefinition();
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
                'config-file',
                null,
                InputOption::VALUE_REQUIRED,
                'File to read doccheck config from in yml format'
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

        return $definition;
    }
}
