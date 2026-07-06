<?php
/**
 * IP 查询缓存
 * 使用 Typecho 自带缓存或文件缓存
 */

class IpCache {
    private static $cacheDir = null;
    private static $cacheTime = 86400; // 缓存24小时
    
    /**
     * 初始化缓存目录
     */
    private static function initCacheDir() {
        if (self::$cacheDir === null) {
            self::$cacheDir = __DIR__ . '/../../cache/ip/';
            if (!is_dir(self::$cacheDir)) {
                mkdir(self::$cacheDir, 0755, true);
            }
        }
    }
    
    /**
     * 获取缓存
     */
    public static function get($ip) {
        self::initCacheDir();
        $key = md5($ip);
        $file = self::$cacheDir . $key . '.cache';
        
        if (file_exists($file) && (time() - filemtime($file) < self::$cacheTime)) {
            return file_get_contents($file);
        }
        return false;
    }
    
    /**
     * 设置缓存
     */
    public static function set($ip, $location) {
        self::initCacheDir();
        $key = md5($ip);
        $file = self::$cacheDir . $key . '.cache';
        file_put_contents($file, $location);
    }
}