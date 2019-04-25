<?php declare(strict_types=1);

/**
 * PHP Docblock Checker
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/php-docblock-checker/blob/master/LICENSE.md
 * @link         http://www.phptesting.org/
 */

namespace PhpDocBlockChecker\Command;

use PhpDocBlockChecker\CacheProvider\CacheProviderFactory;
use PhpDocBlockChecker\Check\Checker;
use PhpDocBlockChecker\Config\ConfigParser;
use PhpDocBlockChecker\Config\ConfigProcessor;
use PhpDocBlockChecker\DocblockParser\DocblockParser;
use PhpDocBlockChecker\FileChecker;
use PhpDocBlockChecker\FileParser\FileParser;
use PhpDocBlockChecker\FileProvider\FileProviderFactory;
use PhpDocBlockChecker\Status\StatusCollection;
use PhpParser\ParserFactory;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
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
     * Configure the console command, add options, etc.
     */
    protected function configure(): void
    {
        $this
            ->setName('check')
            ->setDescription('Check PHP files within a directory for appropriate use of Docblocks.')
            ->addOption(
                'exclude',
                'x',
                InputOption::VALUE_REQUIRED,
                'Files and directories to exclude.'
            )
            ->addOption(
                'directory',
                'd',
                InputOption::VALUE_REQUIRED,
                'Directory to scan.',
                './'
            )
            ->addOption(
                'skip-classes',
                null,
                InputOption::VALUE_NONE,
                'Don\'t check classes for docblocks.'
            )
            ->addOption(
                'skip-methods',
                null,
                InputOption::VALUE_NONE,
                'Don\'t check methods for docblocks.'
            )
            ->addOption(
                'skip-signatures',
                null,
                InputOption::VALUE_NONE,
                'Don\'t check docblocks against method signatures.'
            )
            ->addOption(
                'only-signatures',
                null,
                InputOption::VALUE_NONE,
                'Ignore missing docblocks where method doesn\'t have parameters or return type.'
            )
            ->addOption(
                'json',
                'j',
                InputOption::VALUE_NONE,
                'Output JSON instead of a log.'
            )
            ->addOption(
                'files-per-line',
                'l',
                InputOption::VALUE_REQUIRED,
                'Number of files per line in progress',
                50
            )
            ->addOption(
                'fail-on-warnings',
                'w',
                InputOption::VALUE_NONE,
                'Consider the check failed if any warnings are produced.'
            )
            ->addOption(
                'info-only',
                'i',
                InputOption::VALUE_NONE,
                'Information-only mode, just show summary.'
            )
            ->addOption(
                'from-stdin',
                null,
                InputOption::VALUE_NONE,
                'Use list of files from stdin (e.g. git diff)'
            )
            ->addOption(
                'cache-file',
                null,
                InputOption::VALUE_REQUIRED,
                'Cache analysis of files based on filemtime.'
            )
            ->addOption(
                'config-file',
                null,
                InputOption::VALUE_REQUIRED,
                'File to read doccheck config from in yml format'
            )
            ->addOption(
                'simple-progress',
                null,
                InputOption::VALUE_NONE,
                'Display a simple progress bar'
            );
    }

    /**
     * Execute the actual docblock checker.
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);

        $config = (new ConfigProcessor(
            new ConfigParser($input, $this->getDefinition()),
            $this->getDefinition()
        )
        )->processConfig();

        // Get files to check:
        $files = FileProviderFactory::getFileProvider($config)->getFileIterator();

        // Check files:
        $totalFiles = iterator_count($files);
        $processed = 0;

        $fileChecker = new FileChecker(
            CacheProviderFactory::getCacheProvider($config->getCacheFile()),
            new FileParser(
                (new ParserFactory())->create(ParserFactory::PREFER_PHP7),
                new DocblockParser()
            ),
            new Checker($config)
        );

        $statusCollection = new StatusCollection();

        if ($config->isVerbose()) {
            $output->writeln('');
            $output->writeln('PHP Docblock Checker <fg=blue>by Dan Cryer (https://www.dancryer.com)</>');
            $output->writeln('');

            if ($config->isSimpleProgress()) {
                $progress = new ProgressBar($output, $totalFiles);
            }
        }

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $processed++;

            $status = $fileChecker->checkFile($file->getPathname());
            $statusCollection->addFileStatus($status);

            if ($config->isVerbose()) {
                if ($config->isSimpleProgress() && isset($progress)) {
                    $progress->advance();
                } else {
                    if ($status->hasErrors()) {
                        $output->write('<fg=red>F</>');
                    } elseif ($status->hasWarnings()) {
                        $output->write('<fg=yellow>W</>');
                    } else {
                        $output->write('<info>.</info>');
                    }
                    if ($processed % $config->getFilesPerLine() === 0) {
                        $output->writeln(
                            sprintf(
                                '%s %s/%d (%d%%)',
                                str_pad('', $config->getFilesPerLine() - $processed),
                                str_pad((string)$processed, strlen((string)$totalFiles), ' ', STR_PAD_LEFT),
                                $totalFiles,
                                floor((100 / $totalFiles) * $processed)
                            )
                        );
                    }
                }
            }
        }


        if ($config->isVerbose()) {
            if ($config->isSimpleProgress() && isset($progress)) {
                $progress->finish();
            }

            $time = round(microtime(true) - $startTime, 2);
            $output->writeln('');
            $output->writeln('');
            $output->writeln('Checked ' . number_format($totalFiles) . ' files in ' . $time . ' seconds.');
            $output->write('<info>' . number_format($statusCollection->getTotalPassed()) . ' Passed</info>');
            $output->write(' / <fg=red>' . number_format($statusCollection->getTotalErrors()) . ' Errors</>');
            $output->write(' / <fg=yellow>' . number_format($statusCollection->getTotalWarnings()) . ' Warnings</>');
            $output->write(' / <fg=blue>' . number_format($statusCollection->getTotalInfos()) . ' Info</>');
            $output->writeln('');

            if ($statusCollection->hasErrors() && !$config->isInfoOnly()) {
                $output->writeln('');
                $output->writeln('');

                foreach ($statusCollection->getErrors() as $warning) {
                    $output->writeln($warning->getDecoratedMessage());
                }
            }

            if ($statusCollection->hasInfos() && !$config->isInfoOnly()) {
                $output->writeln('');
                $output->writeln('');

                foreach ($statusCollection->getInfos() as $info) {
                    $output->writeln($info->getDecoratedMessage());
                }
            }

            if ($statusCollection->hasWarnings() && !$config->isInfoOnly()) {
                $output->writeln('');
                $output->writeln('');

                foreach ($statusCollection->getWarnings() as $warning) {
                    $output->writeln($warning->getDecoratedMessage());
                }
            }

            $output->writeln('');
        }

        // Output JSON if requested:
        if ($config->isJson()) {
            print json_encode(array_merge($statusCollection->getErrors(), $statusCollection->getWarnings()));
        }

        return $statusCollection->hasErrors() ||
        ($config->isFailOnWarnings() && $statusCollection->hasWarnings()) ?
            1 : 0;
    }
}
