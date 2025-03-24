<?php

define('DS', DIRECTORY_SEPARATOR);

require __DIR__ . '/classes/parsedown.php';
require __DIR__ . '/classes/parsedown-extra.php';
require __DIR__ . '/classes/parsedown-toc.php';
require __DIR__ . '/classes/engine.php';
require __DIR__ . '/classes/util.php';
require __DIR__ . '/classes/view.php';

(new Engine(require __DIR__ . '/config.php'))->run();
