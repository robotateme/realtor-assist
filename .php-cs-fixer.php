<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notPath('app/Console/Commands/Testing.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12' => true,
        'array_indentation' => true,
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'blank_line_after_opening_tag' => false,
        'cast_spaces' => [
            'space' => 'single',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'declare_strict_types' => false,
        'no_extra_blank_lines' => true,
        'no_unused_imports' => true,
        'new_with_parentheses' => [
            'anonymous_class' => false,
        ],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
        ],
        'single_quote' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
    ])
    ->setFinder($finder);
