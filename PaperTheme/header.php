<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="x-ua-compatiible" content = "IE-edge">
	<meta name = "viewport" content="width-device-width, initial-scale=1">
	<!-- <link href = "<?php bloginfo('stylesheet_url');?>" rel = "stylesheet"> -->
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.min.css" type="text/css" media="all" />
	<!-- <title><?php bloginfo('name'); ?></title> -->
	<?php wp_head(); ?>
	<style>
		body
		{
			background-color: <?php echo of_get_option( 'background_colour', '#f5f5f5');?>;
		}

		.shadow-box-colour
		{
			background-color: <?php echo of_get_option( 'paper_colour', '#FAFAFA');?>;	
		}
		
		.header-text
		{
			color: <?php echo of_get_option( 'header-text-colour', '#444444');?>;
		}

	</style>
	
	<!-- Google Analytics -->
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', '', 'auto');
	  ga('send', 'pageview');

	</script>

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
