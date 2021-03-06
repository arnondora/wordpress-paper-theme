<?php get_header(); ?>
<head><title><?php bloginfo('name'); echo ' - '; bloginfo('description');?></title></head>
	<div class="container contain-content">
		<div class="col-md-9">
			<div class = "hidden-lg hidden-md"> <?php get_search_form(); ?> </div>
			<?php if (have_posts()) : while(have_posts()) : the_post();?>
			<div class="shadow-box shadow-box-colour">
					<a class = 'header-link' href="<?php the_permalink();?>" title = "<?php the_title();?>"><h3 class = "header-text text-center"><?php the_title();?></h3></a>

					<center><?php if (has_post_thumbnail()) {the_post_thumbnail('large',array('class' => 'img-responsive img-thumb'));} ?></center>
					<p class = "content"><?php the_excerpt(); ?></p>
			</div>
			<?php endwhile; else : ?>
				<div class = "shadow-box shadow-box-colour"><h5><?php _e("We can't find what you are looking for."); ?></h5></div>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>
	</div>

			<div class = "content-nav container">
				<?php next_posts_link('Older Entries') ?>
				<?php previous_posts_link('Newer Entries') ?>
			</div>

<?php get_footer(); ?>
