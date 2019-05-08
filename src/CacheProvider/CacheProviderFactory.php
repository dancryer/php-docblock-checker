<?php declare(strict_types=1);

namespace PhpDocBlockChecker\CacheProvider;

/**
 * Class CacheProviderFactory
 */
class CacheProviderFactory
{
    /**
     * @param string|null $cacheParameter
     * @return CacheProviderInterface
     */
    public static function getCacheProvider(?string $cacheParameter): CacheProviderInterface
    {
        if ($cacheParameter === null) {
            return new NullCacheProvider();
        }
        return new JsonFileCacheProvider($cacheParameter);
    }
}
