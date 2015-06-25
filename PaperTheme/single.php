<?php get_header();?>

<!-- Condition if have post -->
<?php if(have_posts()) : while (have_posts()) : the_post();?>
	<div class="container">
		<div class = "shadow-box" style = "padding-top:5px;">
			<a href="<?php the_permalink(); ?>"><h3 class= "text-center"><?php the_title(); ?></h3></a>
			<?php if (has_post_thumbnail()) : ?>
				<div class="row" style = "margin-top:10px;"><center><?php the_post_thumbnail('large',array('class' => 'full-width img-responsive img-thumbnail')); ?></center></div>
			<?php endif; ?>
			<div class="row"><p><?php the_content(); ?></p></div>
		</div>
	</div>
<?php endwhile; else: ?>
<?php endif;?>


<?php get_footer();?>