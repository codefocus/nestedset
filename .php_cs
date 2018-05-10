<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        'align_multiline_comment' => ['comment_type' => 'phpdocs_like'],
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'backtick_to_shell_exec' => true,
        'binary_operator_spaces' => ['align_double_arrow' => false, 'align_equals' => false],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_return' => true,
        'braces' => ['allow_single_line_closure' => false, 'position_after_anonymous_constructs' => 'same', 'position_after_control_structures' => 'same', 'position_after_functions_and_oop_constructs' => 'next'],
        'cast_spaces' => ['space' => 'single'],
        'class_attributes_separation' => true,
        'class_definition' => ['multiLineExtendsEachSingleLine' => false, 'singleItemSingleLine' => true, 'singleLine' => true],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'comment_to_phpdoc' => true,
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'single'],
        'dir_constant' => true,
        'elseif' => true,
        'encoding' => true,
        'escape_implicit_backslashes' => ['single_quoted' => true],
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'full_opening_tag' => true,
        'fully_qualified_strict_types' => true,
        'function_declaration' => ['closure_function_spacing' => 'one'],
        'function_to_constant' => true,
        'function_typehint_space' => true,
        'general_phpdoc_annotation_remove' => true,
        'heredoc_to_nowdoc' => true,
        'include' => true,
        'increment_style' => ['style' => 'pre'],
        'indentation_type' => true,
        'line_ending' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => ['syntax' => 'short'],
        'lowercase_cast' => true,
        'lowercase_constants' => true,
        'lowercase_keywords' => true,
        'magic_constant_casing' => true,
        'mb_str_functions' => true,
        'method_argument_space' => ['ensure_fully_multiline' => true, 'keep_multiple_spaces_after_comma' => false],
        'method_chaining_indentation' => true,
        'modernize_types_casting' => true,
        'multiline_comment_opening_closing' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'native_function_casing' => true,
        'new_with_braces' => true,
        'no_alias_functions' => true,
        'no_alternative_syntax' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        // 'no_blank_lines_before_namespace' => true,
        'no_break_comment' => true,
        'no_closing_tag' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => true,
        'no_homoglyph_names' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => ['use' => 'echo'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_null_property_initialization' => true,
        'no_short_bool_cast' => true,
        'no_short_echo_tag' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_after_function_name' => true,
        'no_spaces_around_offset' => true,
        'no_spaces_inside_parenthesis' => true,
        'no_superfluous_elseif' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_final_method' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'non_printable_character' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'ordered_imports' => true,
        'phpdoc_align' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'pow_to_exponentiation' => true,
        'random_api_migration' => true,
        'return_type_declaration' => ['space_before' => 'none'],
        'self_accessor' => true,
        'semicolon_after_instruction' => true,
        'short_scalar_cast' => true,
        'simplified_null_return' => true,
        'single_blank_line_at_eof' => true,
        'single_blank_line_before_namespace' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'single_line_comment_style' => true,
        'single_quote' => true,
        'standardize_increment' => true,
        'standardize_not_equals' => true,
        'string_line_ending' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline_array' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->in(__DIR__)
    )
;

/*
This document has been generated with
https://mlocati.github.io/php-cs-fixer-configurator/
you can change this configuration by importing this YAML code:

fixers:
  align_multiline_comment:
    comment_type: phpdocs_like
  array_indentation: true
  array_syntax:
    syntax: short
  backtick_to_shell_exec: true
  binary_operator_spaces:
    align_double_arrow: false
    align_equals: false
  blank_line_after_namespace: true
  blank_line_after_opening_tag: true
  blank_line_before_return: true
  braces:
    allow_single_line_closure: false
    position_after_anonymous_constructs: same
    position_after_control_structures: same
    position_after_functions_and_oop_constructs: next
  cast_spaces:
    space: single
  class_attributes_separation: true
  class_definition:
    multiLineExtendsEachSingleLine: false
    singleItemSingleLine: true
    singleLine: true
  combine_consecutive_issets: true
  combine_consecutive_unsets: true
  comment_to_phpdoc: true
  compact_nullable_typehint: true
  concat_space:
    spacing: one
  declare_equal_normalize:
    space: single
  dir_constant: true
  elseif: true
  encoding: true
  escape_implicit_backslashes:
    single_quoted: true
  explicit_indirect_variable: true
  explicit_string_variable: true
  full_opening_tag: true
  fully_qualified_strict_types: true
  function_declaration:
    closure_function_spacing: one
  function_to_constant: true
  function_typehint_space: true
  general_phpdoc_annotation_remove: true
  heredoc_to_nowdoc: true
  include: true
  increment_style:
    style: pre
  indentation_type: true
  line_ending: true
  linebreak_after_opening_tag: true
  list_syntax:
    syntax: short
  lowercase_cast: true
  lowercase_constants: true
  lowercase_keywords: true
  magic_constant_casing: true
  mb_str_functions: true
  method_argument_space:
    ensure_fully_multiline: true
    keep_multiple_spaces_after_comma: false
  method_chaining_indentation: true
  modernize_types_casting: true
  multiline_comment_opening_closing: true
  multiline_whitespace_before_semicolons:
    strategy: no_multi_line
  native_function_casing: true
  new_with_braces: true
  no_alias_functions: true
  no_alternative_syntax: true
  no_blank_lines_after_class_opening: true
  no_blank_lines_after_phpdoc: true
  no_blank_lines_before_namespace: true
  no_break_comment: true
  no_closing_tag: true
  no_empty_comment: true
  no_empty_phpdoc: true
  no_empty_statement: true
  no_extra_blank_lines: true
  no_homoglyph_names: true
  no_leading_import_slash: true
  no_leading_namespace_whitespace: true
  no_mixed_echo_print:
    use: echo
  no_multiline_whitespace_around_double_arrow: true
  no_null_property_initialization: true
  no_short_bool_cast: true
  no_short_echo_tag: true
  no_singleline_whitespace_before_semicolons: true
  no_spaces_after_function_name: true
  no_spaces_around_offset: true
  no_spaces_inside_parenthesis: true
  no_superfluous_elseif: true
  no_trailing_comma_in_list_call: true
  no_trailing_comma_in_singleline_array: true
  no_trailing_whitespace: true
  no_trailing_whitespace_in_comment: true
  no_unneeded_control_parentheses: true
  no_unneeded_curly_braces: true
  no_unneeded_final_method: true
  no_unused_imports: true
  no_useless_else: true
  no_useless_return: true
  no_whitespace_before_comma_in_array: true
  no_whitespace_in_blank_line: true
  non_printable_character: true
  normalize_index_brace: true
  object_operator_without_whitespace: true
  ordered_imports: true
  phpdoc_align: true
  phpdoc_indent: true
  phpdoc_inline_tag: true
  phpdoc_no_access: true
  phpdoc_no_alias_tag: true
  phpdoc_no_empty_return: true
  phpdoc_no_package: true
  phpdoc_no_useless_inheritdoc: true
  phpdoc_order: true
  phpdoc_return_self_reference: true
  phpdoc_scalar: true
  phpdoc_separation: true
  phpdoc_single_line_var_spacing: true
  phpdoc_summary: true
  phpdoc_to_comment: true
  phpdoc_trim: true
  phpdoc_types: true
  phpdoc_var_without_name: true
  pow_to_exponentiation: true
  random_api_migration: true
  return_type_declaration:
    space_before: none
  self_accessor: true
  semicolon_after_instruction: true
  short_scalar_cast: true
  simplified_null_return: true
  single_blank_line_at_eof: true
  single_blank_line_before_namespace: true
  single_class_element_per_statement: true
  single_import_per_statement: true
  single_line_after_imports: true
  single_line_comment_style: true
  single_quote: true
  standardize_increment: true
  standardize_not_equals: true
  string_line_ending: true
  switch_case_semicolon_to_colon: true
  switch_case_space: true
  ternary_operator_spaces: true
  ternary_to_null_coalescing: true
  trailing_comma_in_multiline_array: true
  trim_array_spaces: true
  unary_operator_spaces: true
  visibility_required: true
  whitespace_after_comma_in_array: true
risky: true

*/
