<?php get_header();?>

<!-- Condition if have post -->
<?php if(have_posts()) : while (have_posts()) : the_post();?>
	<div class="container contain-content">
		<!-- Main Content -->
		<div class = "shadow-box-page shadow-box-colour" style = "padding-top:5px;">
			<a href="<?php the_permalink(); ?>"><h3 class= "header-text text-center"><?php the_title(); ?></h3></a>
			<head><title><?php the_title(); echo ' - '; bloginfo('name'); ?></title></head>
			<?php if (has_post_thumbnail()) : ?>
				<div class="row" style = "margin-top:10px;"><center><?php the_post_thumbnail('large',array('class' => 'full-width img-responsive img-thumbnail')); ?></center></div>
			<?php endif; ?>
			<div class="row"><p><?php the_content(); ?></p></div>
		</div>

		<!-- Comment -->
		<div class="shadow-box-page shadow-box-colour" style = "padding-top:5px;"><?php comments_template(); ?></div>
	</div>
<?php endwhile; else: ?>
<?php endif;?>


<?php get_footer();?>
