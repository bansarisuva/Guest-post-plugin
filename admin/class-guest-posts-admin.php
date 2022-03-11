<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/bansarikambariya/
 * @since      1.0.0
 *
 * @package    Guest_Posts
 * @subpackage Guest_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Guest_Posts
 * @subpackage Guest_Posts/admin
 * @author     bansarikambariya <https://profiles.wordpress.org/bansarikambariya/>
 */
class Guest_Posts_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Guest_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Guest_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/guest-posts-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Guest_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Guest_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/guest-posts-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_block_editor_assets_fun() {
		wp_enqueue_script( 'guest-posts-backend-script', plugin_dir_url( __FILE__ ) . 'js/gutenberg-blocks/build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element' ), $this->version, true );
	}


	/**
	 * This function is to register the Custom Post Type
	 */
	public function create_guest_posts() {
		$labels = array(
			'name'               => _x( 'Guest Posts', 'Post Type General Name', 'guest-posts' ),
			'singular_name'      => _x( 'Guest Post', 'Post Type Singular Name', 'guest-posts' ),
			'menu_name'          => __( 'Guest Posts', 'guest-posts' ),
			'parent_item_colon'  => __( 'Parent Guest Posts', 'guest-posts' ),
			'all_items'          => __( 'All Guest Posts', 'guest-posts' ),
			'view_item'          => __( 'View Guest Posts', 'guest-posts' ),
			'add_new_item'       => __( 'Add New Guest Post', 'guest-posts' ),
			'add_new'            => __( 'Add New', 'guest-posts' ),
			'edit_item'          => __( 'Edit Guest Post', 'guest-posts' ),
			'update_item'        => __( 'Update Guest Post', 'guest-posts' ),
			'search_items'       => __( 'Search Guest Post', 'guest-posts' ),
			'not_found'          => __( 'Not Found', 'guest-posts' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'guest-posts' ),
		);

		$args = array(
			'label'               => __( 'guest-posts', 'guest-posts' ),
			'description'         => __( 'Guest Posts', 'guest-posts' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
			'taxonomies'          => array( 'genres' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 80,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,

		);

		register_post_type( 'guest-posts', $args );
	}

	/**
	 * This function is to register the Custom block
	 */
	public function register_dynamic_block() {

		register_block_type(
			'guest-posts/server-side-render',
			array(
				'attributes'      => array(
					'numOfItems' => array(
						'type'    => 'number',
						'default' => 10,
					),
					'postOrder'  => array(
						'type'    => 'string',
						'default' => 'DESC',
					),
				),
				'render_callback' => array( $this, 'guest_post_render_block_callback' ),
			)
		);
	}

	/**
	 * This function is to render post data in the Custom block
	 *
	 * @param      array $attributes       attributes
	 */
	public function guest_post_render_block_callback( $attributes ) {
		$number_of_items = isset( $attributes['numOfItems'] ) && ! empty( $attributes['numOfItems'] ) ? $attributes['numOfItems'] : 10;
		$post_order      = isset( $attributes['postOrder'] ) && ! empty( $attributes['postOrder'] ) ? $attributes['postOrder'] : 'DESC';
		$html            = '';

		$query_args = array(
			'post_type'      => 'guest-posts',
			'posts_per_page' => $number_of_items,
			'order'          => $post_order,
			'post_status'    => 'draft',
		);

		$query_result = new WP_Query( $query_args );

		ob_start();

		if ( $query_result->have_posts() ) {

			while ( $query_result->have_posts() ) {

				$query_result->the_post();
				// single post html
				?>
				<div class="post-info">
					<h4><?php the_title(); ?></h4>
				</div>
				<?php
			}
			wp_reset_postdata();
		}
		$html = ob_get_clean();

		return $html;
	}

}
