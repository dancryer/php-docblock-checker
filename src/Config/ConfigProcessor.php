<?php

namespace PhpDocBlockChecker\Config;

class ConfigProcessor {
   /**
    * @var ConfigParser
    */
   private $configParser;

   /**
    * ConfigProcessor constructor.
    * @param ConfigParser $configParser
    */
   public function __construct(ConfigParser $configParser) {
      $this->configParser = $configParser;
   }

   /**
    * @return Config
    */
   public function processConfig() {
      $config = [];

      // Process options:
      $config['skipClasses'] = $this->configParser->parseOption('skip-classes');
      $config['skipSignatures'] = $this->configParser->parseOption('skip-signatures');
      $config['onlySignatures'] = $this->configParser->parseOption('only-signatures');
      $config['json'] = $this->configParser->parseOption('json');
      $config['failOnWarnings'] = $this->configParser->parseOption('fail-on-warnings');
      $config['infoOnly'] = $this->configParser->parseOption('info-only');
      $config['fromStdin'] = $this->configParser->parseOption('from-stdin');

      // Process parameters

      $config['skipMethods'] = $this->configParser->parseParameter('skip-methods');
      $config['cacheFile'] = $this->configParser->parseParameter('cache-file');
      $config['basePath'] = $this->configParser->parseParameter('directory');
      $config['filesPerLine'] = (int)$this->configParser->parseParameter('files-per-line');

      $config['verbose'] = !$config['json'];

      $exclude = $this->configParser->parseParameter('exclude');
      // Set up excludes:
      if ($exclude !== null) {
         if (!is_array($exclude)) {
            $exclude = array_map('trim', explode(',', $exclude));
         }
         $config['exclude'] = $exclude;
      }

      // Fix conflicting options:
      if ($config['onlySignatures']) {
         $config['skipSignatures'] = false;
      }

      return Config::fromArray($config);
   }
}
