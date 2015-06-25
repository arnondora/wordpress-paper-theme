<?php get_header(); ?>
<head>
	<title>Not Found - <?php bloginfo('name'); ?></title>
</head>

<div class="container">
	<div class="shadow-box block-center row">
		<center>
			<h4>Oopps!!, something goes wrong!</h4>
			<p class=  "text-muted">We can't found what you are looking for. <a href="<?php site_url();?>">Go Home?</a> or Try Search</p>
		</center>
	</div>
	<div class="shadow-box block-center row" style = "padding-top:5px; padding-bottom : 20px;"><?php get_search_form();?></div>
</div>

<?php get_footer(); ?>