<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__.'/src')
    //->in(__DIR__.'/tests')
;

$config = new PhpCsFixer\Config();
return $config->setFinder($finder)
    ->setCacheFile(__DIR__.'/.php_cs.cache');
