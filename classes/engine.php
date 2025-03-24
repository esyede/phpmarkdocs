<?php

defined('DS') or exit('No direct access.');

class Engine
{
    private $home;
    private $version;
    private $revisions = [];
    private $latest;
    private $route;

    private static $parser;
    private static $view;
    private static $config = [];
    private static $versions = [];

    public function __construct(array $config)
    {
        static::$config = $config;
        static::$parser = static::$parser ?: new ParsedownToc();
        static::$view = static::$view ?: new View();
        static::$view->setViewFolder(dirname(__DIR__) . DS . 'themes' . DS . static::getConfig('theme') . DS . 'views');
        static::$view->setCacheFolder(dirname(__DIR__) . DS . 'cache');
        $this->home = static::baseUrl();

        $versions = glob('docs/*', GLOB_ONLYDIR);
        $versions = $versions ?: [];

        foreach ($versions as $dir) {
            $this->revisions[] = preg_replace('~^docs\/~', '', $dir, 1);
        }

        $this->latest = end($this->revisions);
        static::$parser->setBreaksEnabled(Util::config('md_breaks', true));
        static::$parser->setUrlsLinked(Util::config('md_urls', true));
    }

    public function run()
    {
        $this->version = (!isset($_GET['version']) || empty($_GET['version']))
            ? $this->latest
            : trim(mb_strtolower(strval($_GET['version'])));
        $config = 'docs/' . $this->version . '/config.php';

        if (is_file($config)) {
            self::$versions = require $config;
        }

        if (!isset($_GET['route']) || empty($_GET['route'])) {
            $start = Util::config('start_point', 'README');
            $file = 'docs/' . $this->version . '/' . $start . '.md';
            $this->route = pathinfo($start, PATHINFO_FILENAME);
        } elseif ($_GET['route'] === 'search' && (Util::config('search', true))) {
            $this->route = 'search';
            return $this->search();
        } else {
            $this->route = trim(mb_strtolower($_GET['route']));
            $file = 'docs/' . $this->version . '/' . $this->route . '.md';
        }

        if (!is_file($file)) {
            http_response_code(404);
            return $this->view('404', [
                'title' => Util::lang('not_found', 'Page not found') . ' | ' . Util::config('app_name', 'phpMarkDocs'),
                'home' => $this->home,
            ]);
        }

        static::$parser->setTocUrl($this->route);

        $content = @file_get_contents($file);
        $title = strip_tags(trim(str_replace('#', '', strtok($content, "\n"))));
        $content = static::$parser->text($content);
        $content = $this->doReplaces($content);

        return $this->view('main', [
            'title' => $title . ' | ' . Util::config('app_name', 'phpMarkDocs'),
            'menu' => $this->getMenu(),
            'content' => $content,
            'app_name' => Util::config('app_name', 'phpMarkDocs'),
            'git' => Util::config('git_edit', true) ? (trim(Util::config('git_url', ''), '/') . '/' . $file) : '',
            'home' => $this->home,
            'version' => $this->version,
            'versions' => static::sortVersions($this->revisions),
            'latest' => $this->latest,
        ]);
    }

    private function search()
    {
        $query = trim(isset($_GET['q']) ? strval($_GET['q']) : '');

        if (empty($query)) {
            return header('Location: ' . $this->home . $this->version);
        }

        $matches = [];
        $files = $this->globRecursive('docs/' . $this->version . '/*.md');

        foreach ($files as $file) {
            $start = 'docs/' . $this->version . '/' . Util::config('start_point', 'README') . '.md';
            $menu = $file === 'docs/' . $this->version . '/' . Util::config('menu_file', '_menu') . '.md';

            if ($file === $start || $file === $menu) {
                continue;
            }

            $handle = fopen($file, 'r');

            if (!$handle) {
                continue;
            }

            while (!feof($handle)) {
                $buffer = fgets($handle);
                if (mb_stripos($buffer, $query) !== false) {
                    $matches[$file][] = $buffer;
                }
            }

            fclose($handle);
        }

        foreach ($matches as $key => $file) {
            $content = @file_get_contents($key);
            $content = $content ? strval($content) : '';
            $title = strip_tags(trim(str_replace('#', '', strtok($content, "\n"))));

            foreach ($file as &$item) {
                $item = static::$parser->text($item);
                $item = $this->doReplaces($item);
            }

            $url = rtrim(explode('/', $key, 3)[2], '.md');
            $matches[$key] = ['title' => $title ?: '', 'url' => $url, 'results' => $file];
        }

        return $this->view('search', [
            'title' => Util::lang('search_results', 'Search Results') . ' | ' . Util::config('app_name', 'phpMarkDocs'),
            'menu' => $this->getMenu(),
            'results' => $matches,
            'search' => $query,
            'app_name' => Util::config('app_name', 'phpMarkDocs'),
            'home' => $this->home,
            'version' => $this->version,
            'versions' => static::sortVersions($this->revisions),
            'latest' => $this->latest,
        ]);
    }

    private function getMenu()
    {
        $menu = 'docs/' . $this->version . '/' . Util::config('menu_file', '_menu') . '.md';
        $menu = is_file($menu) ? @file_get_contents($menu) : $this->generateMenu();
        return $this->doReplaces(static::$parser->text($menu));
    }

    private function generateMenu()
    {
        if (!Util::config('generate_menu', true)) {
            return '';
        }

        $result = '';
        $files = glob('docs/' . $this->version . '/*.md');

        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);

            if ($name === 'README') {
                continue;
            }

            $name = str_replace('-', ' ', ucfirst($name));
            $link = rtrim(explode('/', $file, 3)[2], '.md');
            $result .= '- [' . $name . '](' . $link . ")\n";
        }

        $dirs = glob('docs/' . $this->version . '/*', GLOB_ONLYDIR);

        foreach ($dirs as $dir) {
            $files = glob($dir . '/*.md');

            if (empty($files)) {
                continue;
            }

            $name = str_replace('-', ' ', ucfirst(pathinfo($dir, PATHINFO_BASENAME)));
            $result .= "\n" . '### ' . $name . "\n";

            foreach ($files as $file) {
                $name = str_replace('-', ' ', ucfirst(pathinfo($file, PATHINFO_FILENAME)));
                $link = str_replace('.md', '', explode('/', $file, 3)[2]);
                $result .= '- [' . $name . '](' . $link . ")\n";
            }
        }

        return $result;
    }

    public function view($view, array $parameters = [])
    {
        static::$view->render($view, $parameters);
    }

    private function doReplaces($content)
    {
        $content = preg_replace('/(?<!\\\)%%version%%/i', $this->version, $content);
        $content = preg_replace('/(?<!\\\)%%latest%%/i', $this->latest, $content);
        $content = preg_replace('/(?<!\\\)%%app%%/i', Util::config('app_name', 'phpMarkDocs'), $content);
        $content = preg_replace('/(?<!\\\)%%route%%/i', $this->route, $content);
        $content = preg_replace('/\\\%%(.+)%%/i', '%%$1%%', $content);
        return $content;
    }

    private function globRecursive($pattern)
    {
        $files = glob($pattern);
        $files = $files ?: [];
        $folders = glob(dirname($pattern) . '/*', GLOB_ONLYDIR);
        $folders = $folders ?: [];

        foreach ($folders as $dir) {
            $files = array_merge($files, $this->globRecursive($dir . '/' . basename($pattern)));
        }

        return $files;
    }

    public static function getConfig($key, $default = null)
    {
        if (isset(self::$versions[$key])) {
            return self::$versions[$key];
        }

        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }

        return $default;
    }

    public static function baseUrl($uri = null)
    {
        return ((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') ? 'https' : 'http')
            . '://' . $_SERVER['HTTP_HOST']
            . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME'])
            . ($uri ? ltrim(strval($uri), '/') : '');
    }

    public static function sortVersions(array $versions)
    {
        $versions = array_values($versions);
        usort($versions, function ($a, $b) {
            return version_compare($a, $b, '>');
        });
        return array_reverse($versions);
    }
}
