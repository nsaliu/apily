<?php
$finder = PhpCsFixer\Finder::create()
    ->exclude('app_data/')
    ->in(__DIR__)
;
return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
    ])
    ->setFinder($finder)
;
