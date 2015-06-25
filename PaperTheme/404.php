<?php get_header(); ?>
<head>
	<title>Not Found - <?php bloginfo('name'); ?></title>
</head>

<div class="container contain-content">
	<div class="shadow-box block-center row">
		<center>
			<h4>Oopps!!, something goes wrong!</h4>
			<p class=  "text-muted">We can't found what you are looking for. <a href="<?php echo get_option('home');?>">Go Home?</a> or Try Search</p>
		</center>
	</div>
	<div class="shadow-box block-center row" style = "padding-top:5px; padding-bottom : 20px;"><?php get_search_form();?></div>
</div>

	<div class = "footer navbar navbar-default navbar-fixed-bottom" style="margin-bottom:0px;">
		<div class="container">
			<p class = "navbar-text pull-left" style = "color:#2196f3;">Copyright 2014-2015 Arnon Puitrakul all right reversed.</p>
			<p class="navbar-text pull-right" style = "color :#2196f3;">Paper Theme by <a href="http://www.arnondora.in.th">@arnondora</a>
		</div>
	</div>