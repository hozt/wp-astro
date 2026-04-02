<?php

// Remove Patterns tab from block inserter
function remove_block_patterns()
{
    remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'remove_block_patterns');
