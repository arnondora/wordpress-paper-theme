<?php get_header();?>

<?php $catid = get_cat_id( single_cat_title("",false) );?>

<head><title>Search result for <?php   echo '"' . $_GET['s'] . '"' . ' - '; bloginfo('name'); ?></title></head>
<div class="container contain-content">

	<?php get_search_form();?>

	<?php if (have_posts()) : while(have_posts()) : the_post();?>
		<div class="shadow-box">
			<a href="<?php the_permalink();?>" title = "<?php the_title();?>"><h3 class = "text-center"><?php the_title();?></h3></a>
			<h6 class = "text-muted text-center">Posted by <?php the_author();?> on <?php the_time('F jS, Y'); ?></h6>

			<center><?php if (has_post_thumbnail()) {the_post_thumbnail('large',array('class' => 'img-responsive img-thumbnail'));} ?></center>
			<p class = "content"><?php the_excerpt(); ?></p>
		</div>
		<?php endwhile; else : ?>
				<p><?php _e('No post right now.'); ?></p>
		<?php endif; ?>
</div>

<?php get_footer();?>