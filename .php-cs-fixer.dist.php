<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'phpdoc_summary' => false,
        'phpdoc_to_comment' => false,
        'no_blank_lines_after_phpdoc' => false,
        'nullable_type_declaration_for_default_null_value' => true,
        'ordered_interfaces' => true,
        'ordered_traits' => true,
        'yoda_style' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
