<?php get_header(); ?>
<head><title><?php bloginfo('name'); echo ' - '; bloginfo('description');?></title></head>
	<div class="container">
		<div class="col-md-9">
			<div class = "hidden-lg hidden-md"> <?php get_search_form(); ?> </div>
			<?php if (have_posts()) : while(have_posts()) : the_post();?>
			<div class="row shadow-box">
					<a href="<?php the_permalink();?>" title = "<?php the_title();?>"><h3 class = "text-center"><?php the_title();?></h3></a>
					<h6 class = "text-muted text-center">Posted by <?php the_author();?> on <?php the_time('F jS, Y'); ?></h6>

					<center><?php if (has_post_thumbnail()) {the_post_thumbnail('large',array('class' => 'img-responsive img-thumbnail'));} ?></center>
					<p class = "content"><?php the_excerpt(); ?></p>
			</div>
			<?php endwhile; else : ?>
				<div class = "row shadow-box"><h5><?php _e("We can't find what you are looking for."); ?></h5></div>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>
	</div>

<?php get_footer(); ?>