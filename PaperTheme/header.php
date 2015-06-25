<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="x-ua-compatiible" content = "IE-edge">
	<meta name = "viewport" content="width-device-width, initial-scale=1">
	<link href = "<?php bloginfo('stylesheet_url');?>" rel = "stylesheet">
	<!-- <title><?php bloginfo('name'); ?></title> -->
	<?php wp_head(); ?>
	<style>
		body
		{
			background-color: <?php echo of_get_option('background_colour');?>;
		}
		h4
		{
			color: <?php echo of_get_option('header_colour');?>;
		}
	</style>
</head>
<body <?php body_class();?> >
	<nav class="navbar navbar-default navbar-static-top">
		<div class="container">
			
			<div class="navbar-header">
				<button class="navbar-toggle collapsed" data-toggle = "collapse" data-target = ".navHeaderCollapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>	
				</button>

				<a href="<?php echo site_url();?>" class="navbar-brand"><?php bloginfo('title'); ?></a>
			</div>

			<div class="collapse navbar-collapse navHeaderCollapse">
				<?php
            		wp_nav_menu( array(
                		'menu'              => 'primary',
                		'theme_location'    => 'primary',
                		'depth'             => 2,
                		'menu_class'        => 'nav navbar-nav',
                		'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                		'walker'            => new wp_bootstrap_navwalker())
            		);
       			?>
			</div>		
		</div>
	</nav>
