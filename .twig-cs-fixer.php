<?php

$finder = new TwigCsFixer\File\Finder();
$finder->in(__DIR__.'/templates');

$config = new TwigCsFixer\Config\Config();
$config->setFinder($finder);
$config->setCacheFile(__DIR__.'/var/.twig-cs-fixer.cache');

return $config;
