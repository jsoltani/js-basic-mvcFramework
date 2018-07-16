<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\RedisCache;

/**
 * jsCache
 *
 * PHP version 7.1
 */
class jsCache {

    /**
     * initial
     * initial cache server
     * @throws \Exception
     */
    public static function initial(){
        static $cache = null;
        if ($cache === null) {
            if(jsConfig::get('CACHE_DRIVER') == 'file'){

                // cache file
                $cache = new FilesystemCache(jsConfig::get('CACHE_FILE'));

            }elseif(jsConfig::get('CACHE_DRIVER') == 'memcache'){

                // memcache
                $memcache = new Memcache();
                $memcacheConfig = jsConfig::get('CACHE_MEMCACHE') ?? [];
                foreach ($memcacheConfig as $servers){
                    $host = $servers['host'] ?? 'localhost';
                    $port = $servers['port'] ?? 11211;

                    $memcache->connect($host, $port);
                }

                $cache = new MemcacheCache();
                $cache->setMemcache($memcache);

            }elseif(jsConfig::get('CACHE_DRIVER') == 'memcached'){

                // memcached
                $memcached = new Memcached();
                $memcacheConfig = jsConfig::get('CACHE_MEMCACHED') ?? [];
                foreach ($memcacheConfig as $servers){
                    $host = $servers['host'] ?? 'localhost';
                    $port = $servers['port'] ?? 11211;

                    $memcached->addServer($host, $port);
                }

                $cache = new MemcachedCache();
                $cache->setMemcached($memcached);

            }elseif(jsConfig::get('CACHE_DRIVER') == 'apc'){

                // apc
                $cache = new ApcCache();

            }elseif(jsConfig::get('CACHE_DRIVER') == 'apcu'){

                // apcu
                $cache = new ApcuCache();

            }elseif(jsConfig::get('CACHE_DRIVER') == 'redis'){

                // redis
                $redis = new Redis();
                $redisConfig = jsConfig::get('CACHE_REDIS') ?? [];
                foreach ($redisConfig as $servers){
                    $host = $servers['host'] ?? 'localhost';
                    $port = $servers['port'] ?? 11211;

                    $redis->connect($host, $port);
                }

                $cache = new RedisCache();
                $cache->setRedis($redis);

            }
        }
        return $cache;
    }

    /**
     * fetch
     * get cache data
     * @param string $id
     * @return bool|false|mixed
     * @throws \Exception
     */
    public static function fetch($id = ''){
        $cache = self::initial();
        if ($cache->contains($id)) {
            return $cache->fetch($id);
        }
        return false;
    }

    /**
     * save
     * save to cache
     *
     * @param string $id
     * @param int|string|array $data
     * @param int $ttl
     * @return bool
     * @throws \Exception
     */
    public static function save($id = '', $data, $ttl = 0){
        $cache = self::initial();
        return $cache->save($id, $data, $ttl);
    }

    /**
     * contains
     * check cache id is exist
     *
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    public static function contains($id = ''){
        $cache = self::initial();
        if ($cache->contains($id)) {
            return true;
        }
        return false;
    }

    /**
     * delete
     * delete cache data by cache id
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id = ''){
        $cache = self::initial();
        if ($cache->contains($id)) {
            return $cache->delete($id);
        }
        return false;
    }

    /**
     * deleteAll
     * delete all cache data
     *
     * @return bool
     * @throws \Exception
     */
    public static function deleteAll(){
        $cache = self::initial();
        return $cache->deleteAll();
    }
}
