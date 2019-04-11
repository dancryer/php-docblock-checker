<?php

namespace PhpDocBlockChecker\Config;

class Config {
   /**
    * @var array
    */
   private $exclude;
   /**
    * @var string
    */
   private $directory;
   /**
    * @var bool
    */
   private $skipClasses = false;
   /**
    * @var bool
    */
   private $skipMethods = false;
   /**
    * @var bool
    */
   private $skipSignatures = false;
   /**
    * @var bool
    */
   private $onlySignatures = false;
   /**
    * @var bool
    */
   private $json;
   /**
    * @var bool
    */
   private $verbose = true;
   /**
    * @var int
    */
   private $filesPerLine;
   /**
    * @var bool
    */
   private $failOnWarnings;
   /**
    * @var bool
    */
   private $infoOnly;
   /**
    * @var bool
    */
   private $fromStdin = false;
   /**
    * @var string
    */
   private $cacheFile;

   /**
    * @param array $data
    * @return Config
    */
   public static function fromArray(array $data) {
      $self = new self();

      foreach ($data as $key => $value) {
         if (property_exists($self, $key)) {
            $self->$key = $value;
         }
      }

      return $self;
   }

   /**
    * @return array
    */
   public function getExclude() {
      return $this->exclude;
   }

   /**
    * @return string
    */
   public function getDirectory() {
      return $this->directory;
   }

   /**
    * @return bool
    */
   public function isSkipClasses() {
      return $this->skipClasses;
   }

   /**
    * @return bool
    */
   public function isSkipMethods() {
      return $this->skipMethods;
   }

   /**
    * @return bool
    */
   public function isSkipSignatures() {
      return $this->skipSignatures;
   }

   /**
    * @return bool
    */
   public function isOnlySignatures() {
      return $this->onlySignatures;
   }

   /**
    * @return bool
    */
   public function isJson() {
      return $this->json;
   }

   /**
    * @return bool
    */
   public function isVerbose() {
      return $this->verbose;
   }

   /**
    * @return int
    */
   public function getFilesPerLine() {
      return $this->filesPerLine;
   }

   /**
    * @return bool
    */
   public function isFailOnWarnings() {
      return $this->failOnWarnings;
   }

   /**
    * @return bool
    */
   public function isInfoOnly() {
      return $this->infoOnly;
   }

   /**
    * @return bool
    */
   public function isFromStdin() {
      return $this->fromStdin;
   }

   /**
    * @return string
    */
   public function getCacheFile() {
      return $this->cacheFile;
   }
}
