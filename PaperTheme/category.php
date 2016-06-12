<?php get_header();?>

<?php $catid = get_cat_id( single_cat_title("",false) );?>

<head><title><?php single_cat_title('',true); echo ' - '; bloginfo('name'); ?></title></head>
<div class="container" style="margin-bottom:75px;">

	<div class="shadow-box shadow-box-colour row" style = "padding-top : 5px;">
		<h5>Category :  <?php single_cat_title('',true); ?></h5>
		<p> <?php echo category_description($catid); ?> </p>
	</div>

	<?php if (have_posts()) : while(have_posts()) : the_post();?>
		<div class="shadow-box-page shadow-box-colour row">
			<a class = 'header-link' href="<?php the_permalink();?>" title = "<?php the_title();?>"><h3 class = "header-text text-center"><?php the_title();?></h3></a>

			<center><?php if (has_post_thumbnail()) {the_post_thumbnail('large',array('class' => 'img-responsive img-thumb'));} ?></center>
			<p class = "content"><?php the_excerpt(); ?></p>
		</div>
		<?php endwhile; else : ?>
				<p><?php _e('No post right now.'); ?></p>
		<?php endif; ?>
</div>

<?php get_footer();?>
