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
		$form = '<div class="container desktop-search-container hidden-sm hidden-xs">
					<form class = "form-inline" role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
						<div class = "row form-group">
							<i class="fa fa-search search-icon col-xs-2" aria-hidden="true"></i>
							<input type="text" class = "form-control search-field col-xs-11" placeholder = "Search topic here" value="' . get_search_query() . '" name="s" id="s" />
						</div>
					</form>
				 </div>

				 <div class="shadow-box shadow-box-colour visible-sm visible-xs" style = "margin-top:-10px;">
		 					<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >
		 						<input type="text" class = "form-control" placeholder = "Search topic here" value="' . get_search_query() . '" name="s" id="s" />
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
				<ul class="list-unstyled nav nav-pills nav-stacked shadow-box shadow-box-colour" style = "padding-top :10px;">
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
			<div class="shadow-box shadow-box-colour" style = "padding-top :10px;">
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
				<div class="shadow-box shadow-box-colour" style = "padding-top : 10px;">
					<ul class="list-unstyled nav nav-pills nav-stacked">
			<?php
					$cat_args['title_li'] = '';
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

	//change page widget style
	class My_Widget_Pages extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_pages', 'description' => __( 'A list of your site&#8217;s Pages.') );
		parent::__construct('pages', __('Pages'), $widget_ops);
	}

	public function widget( $args, $instance ) {
		$title = '<h4>' . apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Pages' ) : $instance['title'], $instance, $this->id_base ) . '</h4>';

		$sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
		$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];

		if ( $sortby == 'menu_order' )
			$sortby = 'menu_order, post_title';
		$out = wp_list_pages( apply_filters( 'widget_pages_args', array(
			'title_li'    => '',
			'echo'        => 0,
			'sort_column' => $sortby,
			'exclude'     => $exclude
		) ) );

		if ( ! empty( $out ) ) {
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
		?>

		<div class="shadow-box shadow-box-colour">
			<ul class = "nav nav-default">
				<?php echo $out; ?>
			</ul>
		</div>
		<?php
			echo $args['after_widget'];
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( in_array( $new_instance['sortby'], array( 'post_title', 'menu_order', 'ID' ) ) ) {
			$instance['sortby'] = $new_instance['sortby'];
		} else {
			$instance['sortby'] = 'menu_order';
		}

		$instance['exclude'] = strip_tags( $new_instance['exclude'] );

		return $instance;
	}

	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'sortby' => 'post_title', 'title' => '', 'exclude' => '') );
		$title = esc_attr( $instance['title'] );
		$exclude = esc_attr( $instance['exclude'] );
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e( 'Sort by:' ); ?></label>
			<select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
				<option value="post_title"<?php selected( $instance['sortby'], 'post_title' ); ?>><?php _e('Page title'); ?></option>
				<option value="menu_order"<?php selected( $instance['sortby'], 'menu_order' ); ?>><?php _e('Page order'); ?></option>
				<option value="ID"<?php selected( $instance['sortby'], 'ID' ); ?>><?php _e( 'Page ID' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude:' ); ?></label> <input type="text" value="<?php echo $exclude; ?>" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id('exclude'); ?>" class="widefat" />
			<br />
			<small><?php _e( 'Page IDs, separated by commas.' ); ?></small>
		</p>
			<?php
		}

	}

	//change meta widget style
	class My_Widget_Meta extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_meta', 'description' => __( "Login, RSS, &amp; WordPress.org links.") );
		parent::__construct('meta', __('Meta'), $widget_ops);
	}

	public function widget( $args, $instance ) {

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = '<h4>'. apply_filters( 'widget_title', empty($instance['title']) ? __( 'Meta' ) : $instance['title'], $instance, $this->id_base ) . '</h4>';

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
			<div class="shadow-box shadow-box-colour">
				<ul class = "nav nav-default">
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
			<?php
				echo apply_filters( 'widget_meta_poweredby', sprintf( '<li><a href="%s" title="%s">%s</a></li>',
					esc_url( __( 'https://wordpress.org/' ) ),
					esc_attr__( 'Powered by WordPress, state-of-the-art semantic personal publishing platform.' ),
					_x( 'WordPress.org', 'meta widget link text' )
				) );

				wp_meta();
		?>
				</ul>
			</div>
	<?php
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags($instance['title']);
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}

	//change calendar widget style
	class My_Widget_Calendar extends WP_Widget {

		public function __construct() {
			$widget_ops = array('classname' => 'widget_calendar', 'description' => __( 'A calendar of your site&#8217;s Posts.') );
			parent::__construct('calendar', __('Calendar'), $widget_ops);
		}

		public function widget( $args, $instance ) {

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = '<h4>' . apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) . '</h4>';

			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			echo '<div class = "shadow-box shadow-box-colour" id="calendar_wrap">';
				echo '<center>' ; get_calendar(); echo '</center>';
			echo '</div>';
			echo $args['after_widget'];
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);

			return $instance;
		}

		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title = strip_tags($instance['title']);
	?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
	<?php
		}
	}

	//change text widget style
	class My_Widget_Text extends WP_Widget {

		public function __construct() {
			$widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML.'));
			$control_ops = array('width' => 400, 'height' => 350);
			parent::__construct('text', __('Text'), $widget_ops, $control_ops);
		}

		public function widget( $args, $instance ) {

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = '<h4>' . apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) . '</h4>';
			$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
			echo $args['before_widget'];
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>
				<div class="textwidget shadow-box shadow-box-colour"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
			<?php
			echo $args['after_widget'];
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			if ( current_user_can('unfiltered_html') )
				$instance['text'] =  $new_instance['text'];
			else
				$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
			$instance['filter'] = ! empty( $new_instance['filter'] );
			return $instance;
		}

		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
			$title = strip_tags($instance['title']);
			$text = esc_textarea($instance['text']);
	?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

			<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
	<?php
		}
	}

	//change Recent Comments widget style
	class My_Widget_Recent_Comments extends WP_Widget {

		public function __construct() {
			$widget_ops = array('classname' => 'widget_recent_comments', 'description' => __( 'Your site&#8217;s most recent comments.' ) );
			parent::__construct('recent-comments', __('Recent Comments'), $widget_ops);
			$this->alt_option_name = 'widget_recent_comments';

			if ( is_active_widget(false, false, $this->id_base) )
				add_action( 'wp_head', array($this, 'recent_comments_style') );

			add_action( 'comment_post', array($this, 'flush_widget_cache') );
			add_action( 'edit_comment', array($this, 'flush_widget_cache') );
			add_action( 'transition_comment_status', array($this, 'flush_widget_cache') );
		}

		public function recent_comments_style() {

			if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
				|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
				return;
			?>
		<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
	<?php
		}

		public function flush_widget_cache() {
			wp_cache_delete('widget_recent_comments', 'widget');
		}

		public function widget( $args, $instance ) {
			global $comments, $comment;

			$cache = array();
			if ( ! $this->is_preview() ) {
				$cache = wp_cache_get('widget_recent_comments', 'widget');
			}
			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			if ( ! isset( $args['widget_id'] ) )
				$args['widget_id'] = $this->id;

			if ( isset( $cache[ $args['widget_id'] ] ) ) {
				echo $cache[ $args['widget_id'] ];
				return;
			}

			$output = '';

			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Comments' );

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			$title = '<h4>' . $title . '</h4>';

			$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
			if ( ! $number )
				$number = 5;

			$comments = get_comments( apply_filters( 'widget_comments_args', array(
				'number'      => $number,
				'status'      => 'approve',
				'post_status' => 'publish'
			) ) );

			$output .= $args['before_widget'];
			if ( $title ) {
				$output .= $args['before_title'] . $title . $args['after_title'];
			}

			$output .= '<div class = "shadow-box shadow-box-colour"><ul id="recentcomments" class = "list-unstyled">';
			if ( $comments ) {
				// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
				$post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
				_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

				foreach ( (array) $comments as $comment) {
					$output .= '<li>';
					/* translators: comments widget: 1: comment author, 2: post link */
					$output .= sprintf( _x( '%1$s on %2$s', 'widgets' ),
						'<span class="comment-author-link">' . get_comment_author_link() . '</span>',
						'<a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a>'
					);
					$output .= '</li>';
				}
			}
			$output .= '</ul></div>';
			$output .= $args['after_widget'];

			echo $output;

			if ( ! $this->is_preview() ) {
				$cache[ $args['widget_id'] ] = $output;
				wp_cache_set( 'widget_recent_comments', $cache, 'widget' );
			}
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['number'] = absint( $new_instance['number'] );
			$this->flush_widget_cache();

			$alloptions = wp_cache_get( 'alloptions', 'options' );
			if ( isset($alloptions['widget_recent_comments']) )
				delete_option('widget_recent_comments');

			return $instance;
		}

		public function form( $instance ) {
			$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
			$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
	?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

			<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show:' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<?php
		}
	}

	//change Tag cloud widget style
	class My_Widget_Tag_Cloud extends WP_Widget {

		public function __construct() {
			$widget_ops = array( 'description' => __( "A cloud of your most used tags.") );
			parent::__construct('tag_cloud', __('Tag Cloud'), $widget_ops);
		}

		public function widget( $args, $instance ) {
			$current_taxonomy = $this->_get_current_taxonomy($instance);
			if ( !empty($instance['title']) ) {
				$title = $instance['title'];
			} else {
				if ( 'post_tag' == $current_taxonomy ) {
					$title = __('Tags');
				} else {
					$tax = get_taxonomy($current_taxonomy);
					$title = $tax->labels->name;
				}
			}

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			$title = '<h4>' . $title . '</h4>';

			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			echo '<div class="tagcloud shadow-box shadow-box-colour">';

			wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array(
				'taxonomy' => $current_taxonomy
			) ) );

			echo "</div>";
			echo $args['after_widget'];
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = strip_tags(stripslashes($new_instance['title']));
			$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
			return $instance;
		}

		public function form( $instance ) {
			$current_taxonomy = $this->_get_current_taxonomy($instance);
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:') ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
		<?php foreach ( get_taxonomies() as $taxonomy ) :
					$tax = get_taxonomy($taxonomy);
					if ( !$tax->show_tagcloud || empty($tax->labels->name) )
						continue;
		?>
			<option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
		<?php endforeach; ?>
		</select></p><?php
		}

		public function _get_current_taxonomy($instance) {
			if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
				return $instance['taxonomy'];

			return 'post_tag';
		}
	}

	//change custom menu widget style
	class My_Nav_Menu_Widget extends WP_Widget {

		public function __construct() {
			$widget_ops = array( 'description' => __('Add a custom menu to your sidebar.') );
			parent::__construct( 'nav_menu', __('Custom Menu'), $widget_ops );
		}

		public function widget($args, $instance) {
			// Get menu
			$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

			if ( !$nav_menu )
				return;

			/** This filter is documented in wp-includes/default-widgets.php */
			$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			if ( !empty($instance['title']) )
				echo $args['before_title'] . '<h4>' .$instance['title'] . '</h4>' . $args['after_title'];

			$nav_menu_args = array(
				'fallback_cb' => '',
				'menu'        => $nav_menu,
				'menu_class'        => 'nav navbar-default shadow-box shadow-box-colour'
			);
			wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args ) );

			echo $args['after_widget'];
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			if ( ! empty( $new_instance['title'] ) ) {
				$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
			}
			if ( ! empty( $new_instance['nav_menu'] ) ) {
				$instance['nav_menu'] = (int) $new_instance['nav_menu'];
			}
			return $instance;
		}

		public function form( $instance ) {
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

			// Get menus
			$menus = wp_get_nav_menus();

			// If no menus exists, direct the user to go and create some.
			if ( !$menus ) {
				echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
				return;
			}
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
				<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ) ?></option>
			<?php
				foreach ( $menus as $menu ) {
					echo '<option value="' . $menu->term_id . '"'
						. selected( $nav_menu, $menu->term_id, false )
						. '>'. esc_html( $menu->name ) . '</option>';
				}
			?>
				</select>
			</p>
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

		//Register Page Widget
		unregister_widget('WP_Widget_Pages');
		register_widget('My_Widget_Pages');

		//Register Meta Widget
		unregister_widget('WP_Widget_Meta');
		register_widget('My_Widget_Meta');

		//Register Calendar Widget
		unregister_widget('WP_Widget_Calendar');
		register_widget('My_Widget_Calendar');

		//Register Text Widget
		unregister_widget('WP_Widget_Text');
		register_widget('My_Widget_Text');

		//Register Recent Comments Widget
		unregister_widget('WP_Widget_Recent_Comments');
		register_widget('My_Widget_Recent_Comments');

		//Register Tag Cloud Widget
		unregister_widget('WP_Widget_Tag_Cloud');
		register_widget('My_Widget_Tag_Cloud');

		//Register Custom Nav Widget
		unregister_widget('WP_Nav_Menu_Widget');
		register_widget('My_Nav_Menu_Widget');

	}
	add_action('widgets_init', 'Register_My_Widget');

	//Theme Settings
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework.php';

	// Loads options.php from child or parent theme
	$optionsfile = locate_template( 'options.php' );
	load_template( $optionsfile );

	// Other settings
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(672 , 372);
	//Add style in next_posts_link() and previous_posts_link() in index.php
	add_filter('next_posts_link_attributes', 'next_link_attributes');
	add_filter('previous_posts_link_attributes', 'previous_link_attributes');

	function previous_link_attributes() {return 'class="btn btn-default pull-right"';}
	function next_link_attributes() {return 'class="btn btn-default pull-left"';}
?>
