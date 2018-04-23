<?php

$config = PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2'                               => true,
        'array_syntax'                        => ['syntax' => 'short'],
        'no_unused_imports'                   => true,
        'ordered_imports'                     => true,
        'no_extra_blank_lines'                => true,
        'no_whitespace_in_blank_line'         => true,
        'no_blank_lines_after_phpdoc'         => true,
        'blank_line_before_statement'         => true,
        'single_blank_line_before_namespace'  => true,
        'single_quote'                        => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align'                        => true,
        'phpdoc_inline_tag'                   => true,
        'binary_operator_spaces'              => [
            'align_double_arrow' => true,
            'align_equals'       => true,
        ],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/core')
            ->in(__DIR__ . '/app')
    )
;

return $config;
