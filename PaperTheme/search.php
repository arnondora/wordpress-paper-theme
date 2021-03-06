<?php get_header();?>

<?php $catid = get_cat_id( single_cat_title("",false) );?>

<head><title>Search result for <?php   echo '"' . $_GET['s'] . '"' . ' - '; bloginfo('name'); ?></title></head>
<div class="container contain-content">

	<?php get_search_form();?>

	<div class="shadow-box shadow-box-colour"><h6>Search Result for : <?php echo $_GET['s'];?></h6></div>

	<?php if (have_posts()) : while(have_posts()) : the_post();?>
		<div class="shadow-box-page shadow-box-colour">
			<a class = 'header-link' href="<?php the_permalink();?>" title = "<?php the_title();?>"><h3 class = "header-text text-center"><?php the_title();?></h3></a>
			<center><?php if (has_post_thumbnail()) {the_post_thumbnail('large',array('class' => 'img-responsive img-thumb'));} ?></center>
			<p class = "content"><?php the_excerpt(); ?></p>
		</div>
		<?php endwhile; else : ?>
			<div class="shadow-box-page shadow-box-colour block-center">
				<center>
					<h4 style = "margin-top:5px;">We can't found what you are looking for.</h4>
					<p class=  "text-muted">Try other keyword or <a href="<?php echo get_option('home');?>">Go Home?</a></p>
				</center>
	</div>		<?php endif; ?>
</div>
<script async src = "<?php echo get_template_directory_uri(); ?>/js/all.min.js"></script>
