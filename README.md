# PHP DocBlock Checker
Check PHP files within a directory for appropriate use of Docblocks.

## Installation
**Composer**:<br>
<code>
composer require block8/php-docblock-checker
</code>

## Usage
**CMD**:<br>
<code>
call vendor/bin/phpdoccheck {params}
</code>

## Parameters

Short | Long | Description
------------ | ------------- | -----------
-h | --help | Display help message.
-x | --exclude=EXCLUDE | Files and directories to exclude.
-d | --directory=DIRECTORY | Directory to scan. [default: "./"]
none | --skip-classes | Don't check classes for docblocks.
none | --skip-methods | Don't check methods for docblocks.
none | --skip-signatures | Don't check docblocks against method signatures.
-j | --json | Output JSON instead of a log.
-l | --files-per-line=FILES-PER-LINE | Number of files per line in progress [default: 50]
-w | --fail-on-warnings | Consider the check failed if any warnings are produced.
-i | --info-only | Information-only mode, just show summary.
-q | --quiet | Do not output any message.
-V | --version | Display this application version.
none | --ansi | Force ANSI output.
none | --no-ansi | Disable ANSI output.
-n | --no-interaction | Do not ask any interactive question.
-v -vv -vvv | --verbose | Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug.
