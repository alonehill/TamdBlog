<?php
/**
 * 
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * 获取 IP 归属地
 */
function get_ip_location($ip) {
    // 特殊IP
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return '本地网络';
    }
    
    // 使用 xdb 查询
    try {
        $dbPath = __DIR__ . '/db/ip2region_v4.xdb';
        if (!file_exists($dbPath)) {
            return '数据库缺失';
        }
        
        $searcher = new Ip2Region("content");
        $region = $searcher->getIpInfo($ip);
    
        if(!is_array($region)){
            return "未知地区";
        }
        $loc = $region['city'];
        $location = trim($loc);
        return $location;
    } catch (Exception $e) {
        return '查询失败';
    }
}


/**
 * 格式化归属地
 */
function format_location($fields) {
    $country = $fields[0] ?? '';
    $province = $fields[2] ?? '';
    $city = $fields[3] ?? '';
    $isp = $fields[4] ?? '';
    
    if ($country === '中国' || $country === '0') {
        $parts = [];
        if ($province && $province !== '0') $parts[] = $province;
        if ($city && $city !== '0' && $city !== $province) $parts[] = $city;
        if ($isp && $isp !== '0') $parts[] = $isp;
        return implode(' ', $parts) ?: '中国';
    }
    return $country;
}

/**
 * 在评论中显示 IP 属地
 */
function show_comment_ip_location($comment) {
    if (empty($comment->ip)) return;
    $location = get_ip_location($comment->ip);
    if ($location && $location !== '查询失败') {
        echo '<span class="comment-ip-location">' . $location . '</span>';
    }
}