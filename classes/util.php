<?php

defined('DS') or exit('No direct access.');

class Util
{
    private static $lang;

    public static function config($key, $default = null)
    {
        return Engine::getConfig($key, $default);
    }

    public static function asset($path)
    {
        $path = '/themes/' . static::config('theme') . '/assets/' . str_replace('\\', '/', ltrim(strval($path), '\\'));
        return Engine::baseUrl($path);
    }

    public static function lang($key, $default = '')
    {
        if (!static::$lang) {
            $file = dirname(__DIR__) . DS . 'lang' . DS . Util::config('lang', 'en') . '.php';
            static::$lang = is_file($file) ? require($file) : [];
        }

        return static::$lang[$key] ?: $default;
    }
}