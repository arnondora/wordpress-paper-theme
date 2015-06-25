<!-- <div class="col-md-3 hidden-xs hidden-sm" style= "margin-top:0px;">
	<h4>Catagories</h4>
	<div class="shadow-box" style = "padding-top : 10px;">
		<ul class="list-unstyled nav nav-pills nav-stacked">
			<?php
				$args = array('orderby' => 'name', 'order' => 'ASC');
				$categories = get_categories($args);
				foreach ($categories as $category)
				{
					echo '<li><a href="'. get_category_link($category-> term_id) .'">'. $category -> name . '</a></li>';
				}
			?>
		</ul>
	</div>
</div> -->

<!-- <div class="col-md-3 hidden-xs hidden-sm" style="margin-top:0px;">
	<h4>Archive</h4>
	<div class="shadow-box" style="padding-top:10px">
		<ul class="list-unstyled nav nav-pills nav-stacked">
			<?php 
			$args = array (
				'type'	=> 'monthly',
				'limit'	=> '',
				'format'=> 'html',
				'before'=> '<li>',
				'after' => '</li>',
				'show_post_count'	=> false,
				'echo'	=> '1',
				'order'	=> 'DESC'
			);
			wp_get_archives($args);
		?>
		</ul>
	</div>
</div> -->

<div class="col-md-3 hidden-xs hidden-sm" style="margin-top:0px;">
	<ul class="list-unstyled nav nav-pills nav-stacked">
		<?php dynamic_sidebar('sidebar-1');?>
	</ul>
</div>