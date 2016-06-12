<?php get_header();?>

<!-- Condition if have post -->
<?php if(have_posts()) : while (have_posts()) : the_post();?>
	<div class="container contain-content">
		<!-- Main Content -->
		<head><title> <?php the_title(); echo ' - '; bloginfo('name') ?></title></head>
		<div class = "shadow-box-page shadow-box-colour" style = "padding-top:5px;">
			<a class = 'header-link' href="<?php the_permalink();?>" title = "<?php the_title();?>"><h3 class = "header-text text-center"><?php the_title();?></h3></a>
			<?php if (has_post_thumbnail()) : ?>
				<div class="row" style = "margin-top:10px;"><center><?php the_post_thumbnail('large',array('class' => 'full-width img-responsive img-thumb')); ?></center></div>
			<?php endif; ?>
			<div class="row content"><p><?php the_content(); ?></p></div>
		</div>

		<!-- Tag and Category -->
		<div class = "shadow-box-no-colour">
			<div class = "align-center">
				<span class = "cat-tag-header">Tags & Catagories<?php echo " -> "; ?></span>
				<?php
					$categories = get_the_category();
					foreach ($categories as $category)
					{
						echo '<a href="' . get_category_link($category->term_id).'" class="cat-tag">' . $category->cat_name. '</a>';
					}

					if (has_tag()) :
					$tags = get_the_tags();
					foreach ($tags as $tag)
					{
						echo '<a href="' . get_tag_link($tag->term_id). '" class = "cat-tag">#' . $tag->name. '</a>';
					}
					endif;
				?>
			</div>
		</div>

		<!-- Comment -->
		<div class="shadow-box-no-colour" style = "padding-top:5px;"><?php comments_template(); ?></div>
	</div>
<?php endwhile; else: ?>
<?php endif;?>


<?php get_footer();?>
