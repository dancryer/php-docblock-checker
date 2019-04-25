<?php declare(strict_types=1);

namespace PhpDocBlockChecker\Config;

/**
 * Class Config
 * @package PhpDocBlockChecker\Config
 */
class Config
{
    /**
     * @var string[]
     */
    private $exclude = [];
    /**
     * @var string
     */
    private $directory = './';
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
     * @var bool
     */
    private $simpleProgress = false;

    /**
     * @param mixed[] $data
     * @return Config
     */
    public static function fromArray(array $data): Config
    {
        $self = new self();

        $data = self::validate($data);

        $self->exclude = $data['exclude'] ?? $self->exclude;
        $self->directory = $data['directory'] ?? $self->directory;
        $self->skipClasses = $data['skip-classes'] ?? $self->skipClasses;
        $self->skipMethods = $data['skip-methods'] ?? $self->skipMethods;
        $self->skipSignatures = $data['skip-signatures'] ?? $self->skipSignatures;
        $self->onlySignatures = $data['only-signatures'] ?? $self->onlySignatures;
        $self->json = $data['json'] ?? $self->json;
        $self->verbose = $data['verbose'] ?? $self->verbose;
        $self->filesPerLine = $data['files-per-line'] ?? $self->filesPerLine;
        $self->failOnWarnings = $data['fail-on-warnings'] ?? $self->failOnWarnings;
        $self->infoOnly = $data['info-only'] ?? $self->infoOnly;
        $self->fromStdin = $data['from-stdin'] ?? $self->fromStdin;
        $self->cacheFile = $data['cache-file'] ?? $self->cacheFile;
        $self->simpleProgress = $data['simple-progress'] ?? $self->simpleProgress;

        return $self;
    }

    /**
     * @param mixed[] $config
     * @return mixed[]
     */
    private static function validate(array $config): array
    {
        // Check base path ends with a slash:

        if (isset($config['directory']) && substr($config['directory'], -1) !== '/') {
            $config['directory'] .= '/';
        }

        if (isset($config['exclude']) && !is_array($config['exclude'])) {
            $config['exclude'] = array_map('trim', explode(',', $config['exclude']));
        }

        if (isset($config['json'])) {
            $isJson = (bool)$config['json'];
            $config['json'] = $isJson;
            $config['verbose'] = !$isJson;
        }

        // Fix conflicting options:
        if (isset($config['only-signatures'])) {
            $isOnlySignatures = (bool)$config['only-signatures'];
            $config['only-signatures'] = $isOnlySignatures;
            if ($isOnlySignatures) {
                $config['skip-signatures'] = false;
            }
        }

        return $config;
    }

    /**
     * @return string[]
     */
    public function getExclude(): array
    {
        return $this->exclude;
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * @return bool
     */
    public function isSkipClasses(): bool
    {
        return $this->skipClasses;
    }

    /**
     * @return bool
     */
    public function isSkipMethods(): bool
    {
        return $this->skipMethods;
    }

    /**
     * @return bool
     */
    public function isSkipSignatures(): bool
    {
        return $this->skipSignatures;
    }

    /**
     * @return bool
     */
    public function isOnlySignatures(): bool
    {
        return $this->onlySignatures;
    }

    /**
     * @return bool
     */
    public function isJson(): bool
    {
        return $this->json;
    }

    /**
     * @return bool
     */
    public function isVerbose(): bool
    {
        return $this->verbose;
    }

    /**
     * @return int
     */
    public function getFilesPerLine(): int
    {
        return $this->filesPerLine;
    }

    /**
     * @return bool
     */
    public function isFailOnWarnings(): bool
    {
        return $this->failOnWarnings;
    }

    /**
     * @return bool
     */
    public function isInfoOnly(): bool
    {
        return $this->infoOnly;
    }

    /**
     * @return bool
     */
    public function isFromStdin(): bool
    {
        return $this->fromStdin;
    }

    /**
     * @return string
     */
    public function getCacheFile(): ?string
    {
        return $this->cacheFile;
    }

    /**
     * @return bool
     */
    public function isSimpleProgress(): bool
    {
        return $this->simpleProgress;
    }
}
