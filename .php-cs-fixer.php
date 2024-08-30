<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        'phpdoc_summary' => false,
        'phpdoc_annotation_without_dot' => true,
        'no_superfluous_phpdoc_tags' => false,
        'concat_space' => false,
        'cast_spaces' => false,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'protected_to_private' => false,
        'phpdoc_no_empty_return' => false,
        'no_useless_else' => false,
        'ordered_class_elements' => ['order' => ['use_trait']],
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'php_unit_test_class_requires_covers' => false,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
    ])
    ->setFinder($finder);
