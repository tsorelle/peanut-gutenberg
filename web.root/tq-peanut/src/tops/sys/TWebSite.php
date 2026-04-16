<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/4/2017
 * Time: 6:51 AM
 */

namespace Tops\sys;


class TWebSite
{
    private static $baseUrl=null;
    public static function GetSiteUrl() {
        global $_SERVER;
        if (!isset($_SERVER['HTTP_HOST'])) {
            return '';
        }
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }

    public static function GetClientIp()
    {
        global $_SERVER;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check if IP is from shared internet
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check if IP is passed from a proxy
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            // Default remote address
            return $_SERVER['REMOTE_ADDR'];
        }
        return 'unknown';
    }

    public static function ExpandUrl($url) {
        $scheme = strtolower(parse_url($url,4)); // PHP_URL_SCHEME
        if ($scheme=='http' || $scheme =='https:') {
            return $url;
        }
        $base = self::GetBaseUrl();
        if (empty($url)) {
            return $base;
        }
        return strpos($url,'/') === 0 ? $base.$url : "$base/$url";
    }

    public static function GetBaseUrl(){
        if (self::$baseUrl!==null) {
            return self::$baseUrl;
        }
        self::$baseUrl = TConfiguration::getValue('url','site');
        if (empty(self::$baseUrl))  {
            self::$baseUrl = self::GetSiteUrl();
        }
        return self::$baseUrl;
    }

    public static function GetDomain() {
        global $_SERVER;
        if (!isset($_SERVER['SERVER_NAME'])) {
            return 'localdomain';
        }
        $parts = explode('.',strtolower($_SERVER['SERVER_NAME']));
        if ($parts[0] == 'www') {
            array_shift($parts);
        }
        return join('.',$parts);
    }

    public static function IsLocalHost() {
        $domain = self::GetDomain();
        return ($domain == 'localhost' || $domain == 'localdomain' || str_starts_with($domain,'local.'));
    }
    public static function reset() {
        self::$baseUrl = null;
    }

    public static function SetBaseUrl($value) {
        $baseUrl = $value;
    }

    /**
     * Get subdomain part of URL
     */
    public static function GetSubdomainName() {
        $domain = strtolower(self::GetDomain());
        $parts = explode('.',$domain);
        $count = count($parts);
        if ($count === 3) {
            if ($parts[0] == 'www') {
                return '';
            }
            return $parts[0];
        }
        return '';
    }


    /**
     * Follows a convention where first part of sub-domain indicates deployment environment.
     * e.g. staging, testing, local.  If no subdomain assume 'production'
     * Unsupported subdomains return 'unknown'
     * Override for a specific installation in settings.ini [site] section...
     * enviornment=(environment name)
     *
     * Returns 'production','staging','testing','local','unknown' or settings value
     */
    public static function GetEnvironmentName() {
        $result = TConfiguration::getValue('environment','site');
        if (!empty($result)) {
            return $result;
        }
        $domain = strtolower(self::GetDomain());
        if ($domain == 'localhost' || starts_with($domain,'local.')) {
            return 'local';
        }
        $parts = explode('.',$domain);
        if (count($parts) == 2) {
            return 'production';
        }
        if (count($parts) == 3) {
            $first = $parts[0];
            if ($first == 'dev' || $first == 'test' || $first == 'testing') {
                return 'testing';
            }
            if ($first == 'staging' || $first == 'stage') {
                return 'staging';
            }
        }
        return 'unknown';
    }

    public static function IsTestEnvironment() {
        $env = self::GetEnvironmentName();
        return ($env == 'testing' || $env == 'staging');
    }

    public static function AppendRequestParams($url,array $params) {
        if (substr($url,-1) == '/') {
            $url = substr($url,0,strlen($url) -1);
        }
        $delim = strpos($url,'?') === false ? '?' : '&';
        foreach ($params as $key => $value) {
            $url .= $delim.$key.'='.$value;
            $delim = '&';
        }
        return $url;
    }
}