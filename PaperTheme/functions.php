<?php
	require_once('wp_bootstrap_navwalker.php');

	//Register Main Menu
	register_nav_menus( array(
    	'primary' => __( 'Primary Menu', 'Testing Theme' ),
	));

	//Register side Sidebar
	add_action( 'widgets_init', 'theme_slug_widgets_init' );
	function theme_slug_widgets_init() 
	{
    	register_sidebar( array(
        	'name' => __( 'Main Sidebar', 'theme-slug' ),
        	'id' => 'sidebar-1',
        	'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
        	'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
    ));
	}

	//change search form style
	function my_search_form( $form ) 
	{
		$form = '<h4 style = "margin-top:10px;">Search</h4>
				 <div class="shadow-box row" style = "padding-top:5px;">
						<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
							<input type="text" class = "form-control" style = "margin-top :-5px;" placeholder = "Search something new!" value="' . get_search_query() . '" name="s" id="s" />
				 		</form>
				 </div>';

	return $form;
	}
	add_filter( 'get_search_form', 'my_search_form' );

	//change recent post widget style
		class My_Recent_Posts extends WP_Widget {

			public function __construct() {
			$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "Your site&#8217;s most recent Posts.") );
			parent::__construct('recent-posts', __('Recent Posts'), $widget_ops);
			$this->alt_option_name = 'widget_recent_entries';

			add_action( 'save_post', array($this, 'flush_widget_cache') );
			add_action( 'deleted_post', array($this, 'flush_widget_cache') );
			add_action( 'switch_theme', array($this, 'flush_widget_cache') );
		}

		public function widget($args, $instance) {
			$cache = array();
			if ( ! $this->is_preview() ) {
				$cache = wp_cache_get( 'widget_recent_posts', 'widget' );
			}

			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			if ( ! isset( $args['widget_id'] ) ) {
				$args['widget_id'] = $this->id;
			}

			if ( isset( $cache[ $args['widget_id'] ] ) ) {
				echo $cache[ $args['widget_id'] ];
				return;
			}

			ob_start();

			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

			/** This filter is documented in wp-includes/default-widgets.php */
			//$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			$title = '<h4>' . $title . '</h4>'; //change to my style

			$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
			if ( ! $number )
				$number = 5;
			$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

			/**
			 * Filter the arguments for the Recent Posts widget.
			 *
			 * @since 3.4.0
			 *
			 * @see WP_Query::get_posts()
			 *
			 * @param array $args An array of arguments used to retrieve the recent posts.
			 */
			$r = new WP_Query( apply_filters( 'widget_posts_args', array(
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true
			) ) );

			if ($r->have_posts()) :
	?>
			<?php echo $args['before_widget']; ?>
			<?php if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>
				<ul class="list-unstyled nav nav-pills nav-stacked shadow-box row" style = "padding-top :10px;">
				<?php while ( $r->have_posts() ) : $r->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
					<?php if ( $show_date ) : ?>
						<span class="post-date"><?php echo get_the_date(); ?></span>
					<?php endif; ?>
					</li>
				<?php endwhile; ?>
				</ul>
			<?php echo $args['after_widget']; ?>
	<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

			endif;

			if ( ! $this->is_preview() ) {
				$cache[ $args['widget_id'] ] = ob_get_flush();
				wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
			} else {
				ob_end_flush();
			}
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['number'] = (int) $new_instance['number'];
			$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
			$this->flush_widget_cache();

			$alloptions = wp_cache_get( 'alloptions', 'options' );
			if ( isset($alloptions['widget_recent_entries']) )
				delete_option('widget_recent_entries');

			return $instance;
		}

		public function flush_widget_cache() {
			wp_cache_delete('widget_recent_posts', 'widget');
		}

		public function form( $instance ) {
			$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
			$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
			$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
	?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

			<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
	<?php
		}
	}

	//change archives widget style
		/**
	 * Archives widget class
	 *
	 * @since 2.8.0
	 */
	class My_Widget_Archives extends WP_Widget {

		public function __construct() {
			$widget_ops = array('classname' => 'widget_archive', 'description' => __( 'A monthly archive of your site&#8217;s Posts.') );
			parent::__construct('archives', __('Archives'), $widget_ops);
		}

		public function widget( $args, $instance ) {
			$c = ! empty( $instance['count'] ) ? '1' : '0';
			$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Archives' ) : $instance['title'], $instance, $this->id_base );
			$title = '<h4>' . $title . '</h4>';

			echo $args['before_widget'];
			if ( $title ) {
				echo '<h4>' . $args['before_title'] . $title . $args['after_title']  . '</h4>';
			}

			if ( $d ) {
				$dropdown_id = "{$this->id_base}-dropdown-{$this->number}";
	?>
			<label class="screen-reader-text" for="<?php echo esc_attr( $dropdown_id ); ?>"><?php echo $title; ?></label>
			<select id="<?php echo esc_attr( $dropdown_id ); ?>" name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'>
				<?php
				/**
				 * Filter the arguments for the Archives widget drop-down.
				 *
				 * @since 2.8.0
				 *
				 * @see wp_get_archives()
				 *
				 * @param array $args An array of Archives widget drop-down arguments.
				 */
				$dropdown_args = apply_filters( 'widget_archives_dropdown_args', array(
					'type'            => 'monthly',
					'format'          => 'option',
					'show_post_count' => $c
				) );

				switch ( $dropdown_args['type'] ) {
					case 'yearly':
						$label = __( 'Select Year' );
						break;
					case 'monthly':
						$label = __( 'Select Month' );
						break;
					case 'daily':
						$label = __( 'Select Day' );
						break;
					case 'weekly':
						$label = __( 'Select Week' );
						break;
					default:
						$label = __( 'Select Post' );
						break;
				}
				?>

				<option value=""><?php echo esc_attr( $label ); ?></option>
				<?php wp_get_archives( $dropdown_args ); ?>

			</select>
	<?php
			} else {
	?>
			<div class="shadow-box row" style = "padding-top :10px;">
				<ul class="list-unstyled nav nav-pills nav-stacked">
	<?php
			/**
			 * Filter the arguments for the Archives widget.
			 *
			 * @since 2.8.0
			 *
			 * @see wp_get_archives()
			 *
			 * @param array $args An array of Archives option arguments.
			 */
			wp_get_archives( apply_filters( 'widget_archives_args', array(
				'type'            => 'monthly',
				'show_post_count' => $c
			) ) );
	?>
				</ul>
			</div>
	<?php
			}

			echo $args['after_widget'];
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['count'] = $new_instance['count'] ? 1 : 0;
			$instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;

			return $instance;
		}

		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
			$title = strip_tags($instance['title']);
			$count = $instance['count'] ? 'checked="checked"' : '';
			$dropdown = $instance['dropdown'] ? 'checked="checked"' : '';
	?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
			<p>
				<input class="checkbox" type="checkbox" <?php echo $dropdown; ?> id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>" /> <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e('Display as dropdown'); ?></label>
				<br/>
				<input class="checkbox" type="checkbox" <?php echo $count; ?> id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" /> <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Show post counts'); ?></label>
			</p>
	<?php
		}
	}

	//change categories widget style
		/**
		 * Categories widget class
		 *
		 * @since 2.8.0
		 */
		class My_Widget_Categories extends WP_Widget {

			public function __construct() {
				$widget_ops = array( 'classname' => 'widget_categories', 'description' => __( "A list or dropdown of categories." ) );
				parent::__construct('categories', __('Categories'), $widget_ops);
			}

			public function widget( $args, $instance ) {

				/** This filter is documented in wp-includes/default-widgets.php */
				$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base );
				$title = '<h4>' . $title . '</h4>';

				$c = ! empty( $instance['count'] ) ? '1' : '0';
				$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
				$d = ! empty( $instance['dropdown'] ) ? '1' : '0';

				echo $args['before_widget'];
				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}

				$cat_args = array(
					'orderby'      => 'name',
					'show_count'   => $c,
					'hierarchical' => $h
				);

				if ( $d ) {
					static $first_dropdown = true;

					$dropdown_id = ( $first_dropdown ) ? 'cat' : "{$this->id_base}-dropdown-{$this->number}";
					$first_dropdown = false;

					echo '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>';

					$cat_args['show_option_none'] = __( 'Select Category' );
					$cat_args['id'] = $dropdown_id;

					/**
					 * Filter the arguments for the Categories widget drop-down.
					 *
					 * @since 2.8.0
					 *
					 * @see wp_dropdown_categories()
					 *
					 * @param array $cat_args An array of Categories widget drop-down arguments.
					 */
					wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $cat_args ) );

		?>

		<script type='text/javascript'>
		/* <![CDATA[ */
		(function() {
			var dropdown = document.getElementById( "<?php echo esc_js( $dropdown_id ); ?>" );
			function onCatChange() {
				if ( dropdown.options[ dropdown.selectedIndex ].value > 0 ) {
					location.href = "<?php echo home_url(); ?>/?cat=" + dropdown.options[ dropdown.selectedIndex ].value;
				}
			}
			dropdown.onchange = onCatChange;
		})();
		/* ]]> */
		</script>

		<?php
				} else {
		?>
				<div class="shadow-box row" style = "padding-top : 10px;">
					<ul class="list-unstyled nav nav-pills nav-stacked">
			<?php
					$cat_args['title_li'] = '';

					/**
					 * Filter the arguments for the Categories widget.
					 *
					 * @since 2.8.0
					 *
					 * @param array $cat_args An array of Categories widget options.
					 */
					wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) );
			?>
					</ul>
				</div>
		<?php
				}

				echo $args['after_widget'];
			}

			public function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
				$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
				$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

				return $instance;
			}

			public function form( $instance ) {
				//Defaults
				$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
				$title = esc_attr( $instance['title'] );
				$count = isset($instance['count']) ? (bool) $instance['count'] :false;
				$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
				$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

				<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
				<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
				<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
				<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
		<?php
			}

		}



	//Register custom widget
	function Register_My_Widget()
	{
		//Register Recent Posts Widget
		unregister_widget('WP_Widget_Recent_Posts');
		register_widget('My_Recent_Posts');

		//Register Archives Widget
		unregister_widget('WP_Widget_Archives');
		register_widget('My_Widget_Archives');

		//Register Category Widget
		unregister_widget('WD_Widget_Categories');
		register_widget('My_Widget_Categories');
	}
	add_action('widgets_init', 'Register_My_Widget');

	//Theme Settings
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework.php';

	// Loads options.php from child or parent theme
	$optionsfile = locate_template( 'options.php' );
	load_template( $optionsfile );

	// Other settings
	add_theme_support('post_thumbnails');
	set_post_thumbnail_size(672 , 372);
?>