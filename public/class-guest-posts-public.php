<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/bansarikambariya/
 * @since      1.0.0
 *
 * @package    Guest_Posts
 * @subpackage Guest_Posts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Guest_Posts
 * @subpackage Guest_Posts/public
 * @author     bansarikambariya <https://profiles.wordpress.org/bansarikambariya/>
 */
class Guest_Posts_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/guest-posts-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/guest-posts-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'guestObj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * This function is provided to display guest posts data entry form in frontend
	 */
	public function display_guest_posts_form() {
		ob_start();
		if ( current_user_can( 'author' ) && is_user_logged_in() ) {

			$args       = array(
				'public'   => true,
				'_builtin' => false,
			);
			$output     = 'names';
			$operator   = 'and';
			$post_types = get_post_types( $args, $output, $operator );

			?>

			<div class = "success_msg" style="display: none;">
				<h4>The Post has been added Successfully</h4>
			</div>
			<form method="post" enctype="multipart/form-data" id="guest-post">
				<label for="post-title">Post Title:</label>
				<input type="textbox" name="post_title" id="post_title" class="space"/><br>
				<label for="post-type">Post Type:</label>
				<select name="custom_post_type" id="custom_post_type" class="space">
					<option> </option>
					<?php foreach ( $post_types  as $post_type ) { ?>
						<option value="<?php echo esc_attr( $post_type ); ?>"><?php echo esc_html( $post_type ); ?></option>
					<?php } ?>
				</select><br>

				<label for="post-content">Description:</label>
				<?php
					wp_editor(
						'',
						'post_content',
						array(
							'textarea_name' => 'post_content',
							'media_buttons' => false,
						)
					);
				?>
				<label for="post-excerpt">Excerpt:</label>
				<textarea name="post_excerpt" id="post_excerpt"></textarea><br>
				<label for="featuredImg">Featured Image:</label>
				<input type="file" name="file"  id="file"  />
				<div class="error1" style="display: none;">Please select valid file</div><br>
				<div class="error" style="display: none;">No files are selected</div><br>
				<input type="submit" value="Submit" name="submit" id="submit">
			</form>
			<div id='loader' style='display: none;'>
				<img src='<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>loader.gif' width='32px' height='32px'>
			</div>
			<?php
		}
		return ob_get_clean();
	}

	/**
	 * This function is for form submit through ajax
	 */
	public function guest_posts_form_submit() {
		$postData = array(
			'post_title'   => $_POST['post_title'],
			'post_content' => $_POST['post_content'],
			'post_excerpt' => $_POST['post_excerpt'],
			'post_status'  => 'draft',
			'post_type'    => $_POST['post_type'],
		);
		$post_id  = wp_insert_post( $postData );
		if ( isset( $_FILES['postImg'] ) ) {
			$files = $_FILES['postImg'];

			if ( $files['name'] ) {
				$file   = array(
					'name'     => $files['name'],
					'type'     => $files['type'],
					'tmp_name' => $files['tmp_name'],
					'error'    => $files['error'],
					'size'     => $files['size'],
				);
				$_FILES = array( 'file' => $file );
				foreach ( $_FILES as $file => $array ) {
					if ( isset( $_FILES[ $file ]['error'] ) ) {
						if ( $_FILES[ $file ]['error'] !== UPLOAD_ERR_OK ) {
							__return_false();
						}

						require_once ABSPATH . 'wp-admin/includes/image.php';
						require_once ABSPATH . 'wp-admin/includes/file.php';
						require_once ABSPATH . 'wp-admin/includes/media.php';

						$attach_id = media_handle_upload( $file, $post_id );

						set_post_thumbnail( $post_id, $attach_id );
					}
				}
			}
		}
		$this->send_email_on_draft( $post_id );

		return 'success';
	}

	/**
	 * This function is for sending email to admin for draft post
	 *
	 * @param      string $post_id       Post Id.
	 */
	public function send_email_on_draft( $post_id ) {
		$admin_url  = get_bloginfo( 'admin_email' );
		$post_title = get_the_title( $post_id );
		$post_url   = get_permalink( $post_id );
		$subject    = 'A post has been added';
		$message    = 'A post has been added on your website:   ';
		$message   .= $post_title . ' URL :  ' . $post_url;
		wp_mail( $admin_url, $subject, $message );

	}

	/**
	 * This function is to display all pending custom post type (guest posts)
	 */
	public function display_all_pending_guest_posts() {
		ob_start();

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$args = array(
			'post_type'      => 'guest-posts',
			'orderby'        => 'date',
			'order'          => 'ASC',
			'post_status'    => 'draft',
			'posts_per_page' => 10,
			'paged'          => $paged,
		);

		$query = new WP_Query( $args );
		?>
		<div class="guest-posts">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			?>
			<h2>
			<?php the_title(); ?>
			</h2>
			<?php $url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' ); ?>
			<div class="col">
			<div class="guest-post-img">
				<img src="<?php echo esc_url( $url ); ?>" />
			</div>
			<div class="guest-post-content">
				<p>
				<?php esc_html( the_excerpt() ); ?>
				</p>
			</div>
			</div>
			<?php
			endwhile;
		?>
		</div>
		<div class="guest-post-pagination">
			<?php
			$big = 999999999;
			echo wp_kses_post(
				paginate_links(
					array(
						'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'  => '?paged=%#%',
						'current' => max( 1, get_query_var( 'paged' ) ),
						'total'   => $query->max_num_pages,
					)
				)
			);
			?>
		</div>
		<?php
		return ob_get_clean();
	}
}
