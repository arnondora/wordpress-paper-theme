<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	// Change this to use your theme slug
	return 'options-framework-theme';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'theme-textdomain'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {
	$options[] = array(
		'name' => __( 'Colour', 'theme-textdomain' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Background Colour', 'theme-textdomain' ),
		'desc' => __( '', 'theme-textdomain' ),
		'id' => 'background_colour',
		'std' => '#f5f5f5',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Paper Colour', 'theme-textdomain' ),
		'desc' => __( '', 'theme-textdomain' ),
		'id' => 'paper_colour',
		'std' => '#FAFAFA',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Header Text Colour', 'theme-textdomain' ),
		'desc' => __( '', 'theme-textdomain' ),
		'id' => 'header-text-colour',
		'std' => '#444444',
		'type' => 'color'
	);	

	return $options;
}