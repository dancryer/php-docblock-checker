<?php
/**
 * PHP Docblock Checker
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/php-docblock-checker/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PhpDocblockChecker;

use DirectoryIterator;
use PHP_Token_Stream;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console command to check a directory of PHP files for Docblocks.
 * @author Dan Cryer <dan@block8.co.uk>
 */
class CheckerCommand extends Command
{
    /**
     * @var string
     */
    protected $basePath = './';

    /**
     * @var bool
     */
    protected $verbose = true;

    /**
     * @var array
     */
    protected $report = array();

    /**
     * @var array
     */
    protected $exclude = array();

    /**
     * @var bool
     */
    protected $skipClasses = false;

    /**
     * @var bool
     */
    protected $skipMethods = false;

    /**
     * @var OutputInterface
     */
    protected $output;

    /** @var int */
    protected $passed = 0;

    /**
     * Configure the console command, add options, etc.
     */
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDescription('Check PHP files within a directory for appropriate use of Docblocks.')
            ->addOption('exclude', 'x', InputOption::VALUE_REQUIRED, 'Files and directories to exclude.', null)
            ->addOption('directory', 'd', InputOption::VALUE_REQUIRED, 'Directory to scan.', './')
            ->addOption('skip-classes', null, InputOption::VALUE_NONE, 'Don\'t check classes for docblocks.')
            ->addOption('skip-methods', null, InputOption::VALUE_NONE, 'Don\'t check methods for docblocks.')
            ->addOption('skip-anonymous-functions', null, InputOption::VALUE_NONE, 'Don\'t check anonymous functions for docblocks.')
            ->addOption('json', 'j', InputOption::VALUE_NONE, 'Output JSON instead of a log.')
            ->addOption('files-per-line', 'l', InputOption::VALUE_REQUIRED, 'Number of files per line in progress', 50)
            ->addOption('info-only', 'i', InputOption::VALUE_NONE, 'Information-only mode, just show summary.');
    }

    /**
     * Execute the actual docblock checker.
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Process options:
        $exclude = $input->getOption('exclude');
        $json = $input->getOption('json');
        $this->basePath = $input->getOption('directory');
        $this->verbose = !$json;
        $this->output = $output;
        $this->skipClasses = $input->getOption('skip-classes');
        $this->skipMethods = $input->getOption('skip-methods');

        // Set up excludes:
        if (!is_null($exclude)) {
            $this->exclude = array_map('trim', explode(',', $exclude));
        }

        // Check base path ends with a slash:
        if (substr($this->basePath, -1) != '/') {
            $this->basePath .= '/';
        }

        // Get files to check:
        $files = [];
        $this->processDirectory('', $files);

        // Check files:
        $filesPerLine = (int)$input->getOption('files-per-line');
        $totalFiles = count($files);
        $files = array_chunk($files, $filesPerLine);
        $processed = 0;
        $fileCountLength = strlen((string)$totalFiles);

        while (count($files)) {
            $chunk = array_shift($files);
            $chunkFiles = count($chunk);

            while (count($chunk)) {
                $processed++;
                $file = array_shift($chunk);

                if ($this->processFile($file)) {
                    $this->output->write('<info>.</info>');
                } else {
                    $this->output->write('<fg=red>F</>');
                }
            }

            $this->output->write(str_pad('', $filesPerLine - $chunkFiles));
            $this->output->writeln('  ' . str_pad($processed, $fileCountLength, ' ', STR_PAD_LEFT) . '/' . $totalFiles . ' (' . floor((100/$totalFiles) * $processed) . '%)');
        }

        if ($this->verbose) {
            $this->output->writeln('');
            $this->output->writeln('');
            $this->output->writeln($totalFiles . ' Files Checked.');
            $this->output->writeln('<info>' . $this->passed . ' Passed</info> / <fg=red>'.count($this->report).' Errors</>');

            if (count($this->report) && !$input->getOption('info-only')) {
                $this->output->writeln('');
                $this->output->writeln('');

                foreach ($this->report as $error) {
                    $this->output->write('<error>' . $error['file'] . ':' . $error['line'] . '</error> - ');

                    if ($error['type'] == 'class') {
                        $this->output->write('Class <info>' . $error['class'] . '</info> is missing a docblock.');
                    }

                    if ($error['type'] == 'method') {
                        $this->output->write('Method <info>' . $error['class'] . '::' . $error['method'] . '</info> is missing a docblock.');
                    }

                    $this->output->writeln('');
                }
            }

            $this->output->writeln('');
        }



        // Output JSON if requested:
        if ($json) {
            print json_encode($this->report);
        }

        return count($this->report) ? 1 : 0;
    }

    /**
     * Iterate through a directory and check all of the PHP files within it.
     * @param string $path
     */
    protected function processDirectory($path = '', array &$worklist = [])
    {
        $dir = new DirectoryIterator($this->basePath . $path);

        foreach ($dir as $item) {
            if ($item->isDot()) {
                continue;
            }

            $itemPath = $path . $item->getFilename();

            if (in_array($itemPath, $this->exclude)) {
                continue;
            }

            if ($item->isFile() && $item->getExtension() == 'php') {
                $worklist[] = $itemPath;
            }

            if ($item->isDir()) {
                $this->processDirectory($itemPath . '/', $worklist);
            }
        }
    }

    /**
     * Check a specific PHP file for errors.
     * @param $file
     * @return bool
     */
    protected function processFile($file)
    {
        $errors = false;
        $stream = new PHP_Token_Stream($this->basePath . $file);

        foreach($stream->getClasses() as $name => $class) {
            if (!$this->skipClasses && is_null($class['docblock'])) {
                $errors = true;
                $this->report[] = array(
                    'type' => 'class',
                    'file' => $file,
                    'class' => $name,
                    'line' => $class['startLine'],
                );
            }

            if (!$this->skipMethods) {
                foreach ($class['methods'] as $methodName => $method) {
                    if ($methodName == 'anonymous function') {
                        continue;
                    }

                    if (is_null($method['docblock'])) {
                        $errors = true;
                        $this->report[] = array(
                            'type' => 'method',
                            'file' => $file,
                            'class' => $name,
                            'method' => $methodName,
                            'line' => $method['startLine'],
                        );
                    }
                }
            }
        }

        if (!$errors) {
            $this->passed += 1;
        }

        return !$errors;
    }
}
