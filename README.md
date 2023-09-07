# PHP DocBlock Checker
Check PHP files within a directory for appropriate use of Docblocks. This is a fork of the original work from @dancryer but just adding support for newer php features

## Installation
### Composer
`composer require neild3r/php-docblock-checker`

## Usage
### CMD
`call vendor/bin/phpdoccheck {params}`

To validate changed files in the last git commit:

`git diff --name-only HEAD HEAD^ | ./vendor/bin/phpdoccheck --from-stdin`

If used within a travis context, this may be useful:

`git diff --name-only ${TRAVIS_COMMIT_RANGE:-"HEAD^"} | ./vendor/bin/phpdoccheck --from-stdin`

## Parameters

Parameters may be passed either from the command line, or via a config file (defaults to `phpdoccheck.yml` in the currrent working directory).

The config file location may be overriden by specifying the `--config-file` option

If a parameter is specified in both places, the command line will take priority.

Short | Long | Description
------------ | ------------- | -----------
-h | --help | Display help message.
-x | --exclude=EXCLUDE | Files and directories (absolute or pattern) to exclude.
-d | --directory=DIRECTORY | Directory to scan. [default: "./"]
none | --cache-file=FILE | Use cache file to speed up processing.
none | --config-file=FILE | Use config file to specify options [default: "./phpdoccheck.yml"].
none | --from-stdin | Use list of files provided via stdin
none | --skip-classes | Don't check classes for docblocks.
none | --skip-methods | Don't check methods for docblocks.
none | --skip-signatures | Don't check docblocks against method signatures.
none | --only-signatures | Only check methods that have parameters or returns.
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

Each option is also available in the config file:

```yaml
directory: src
files-per-line: 10
cache-file: .phpdoccheck
exclude:
  - foo/bar/baz.php
  - foo/*
options:
  - skip-classes
  - skip-methods
  - skip-signatures
  - only-signatures
  - fail-on-warnings
  - info-only
  - from-stdin
  - json
```
