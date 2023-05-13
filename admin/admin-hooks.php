<?php
/**
 * Functions and definitions
 *
 * @package WordPress
 * 
 * @link URL
 * 
 * @category League
 * 
 * 
 */

// function for enque upload image scripts.
if ( ! function_exists("nsl_spt_league_image_uploader_enqueue") ) {
    /**
     * Functions and definitions
     */
    function nsl_spt_league_image_uploader_enqueue() {
        global $typenow,  $pagenow;
        if( $typenow == 'nsl-sports-team' || $pagenow == 'term.php'  &&  $pagenow == 'edit-tags.php' ) {
            wp_enqueue_media();
            wp_register_script( 'meta-image', plugin_dir_url( __FILE__ ).'/js/media-uploader.js', array( 'jquery' ) );
            wp_localize_script( 'meta-image', 'meta_image',
            array(
            'title' => __('Upload an Image',"nsl-sports-league-ml"),
            'button' => __('Remove Image',"nsl-sports-league-ml"),
            )
            );
            wp_enqueue_script( 'meta-image' );
        }
    }
}

add_action( 'admin_enqueue_scripts', 'nsl_spt_league_image_uploader_enqueue' );
/* Registering post type for Elementor widget with 'nsl-sports-league' */
function nsl_spt_league_custom_post_type() {
	$labels = array(
		'name'                => _x( 'Sports team', 'Post Type General Name', 'nsl-sports-league-ml' ),
		'singular_name'       => _x( 'Sports team', 'Post Type Singular Name', 'nsl-sports-league-ml' ),
		'menu_name'           => __( 'Sports teams', 'nsl-sports-league-ml' ),
		'parent_item_colon'   => __( 'Parent Sports team', 'nsl-sports-league-ml' ),
		'all_items'           => __( 'All Sports team', 'nsl-sports-league-ml' ),
		'view_item'           => __( 'View Sports team', 'nsl-sports-league-ml' ),
		'add_new_item'        => __( 'Add New Sports team', 'nsl-sports-league-ml' ),
		'add_new'             => __( 'Add New', 'nsl-sports-league-ml' ),
		'edit_item'           => __( 'Edit Sports team', 'nsl-sports-league-ml' ),
		'update_item'         => __( 'Update Sports team', 'nsl-sports-league-ml' ),
		'search_items'        => __( 'Search Sports team', 'nsl-sports-league-ml' ),
		'not_found'           => __( 'Not Found', 'nsl-sports-league-ml' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'nsl-sports-league-ml' )
	);
	// Set other options for Custom Post Type
	$args = array(
		'label'               => __( 'Sports team', 'nsl-sports-league-ml' ),
		'description'         => __( 'Sports teams is an Elementer widget post type.', 'nsl-sports-league-ml' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt',  'thumbnail'  ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-buddicons-tracking' ,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
		'show_in_rest'        => false
	);
	register_post_type( 'nsl-sports-team', $args ); 
} 
add_action( 'init', 'nsl_spt_league_custom_post_type', 0 );
/* Registering taxonomy for Elementor widget with 'nsl-sports-league' post type */  
function nsl_spt_league_nonhierarchical_taxonomy() {
	$labels = array(
		'name' => _x( 'League', 'taxonomy general name', 'nsl-sports-league-ml' ),
		'singular_name' => _x( 'League', 'taxonomy singular name', 'nsl-sports-league-ml' ),
		'search_items' =>  __( 'Search League', 'nsl-sports-league-ml' ),
		'popular_items' => __( 'Popular League', 'nsl-sports-league-ml' ),
		'all_items' => __( 'All League', 'nsl-sports-league-ml' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit League', 'nsl-sports-league-ml' ), 
		'update_item' => __( 'Update League', 'nsl-sports-league-ml' ),
		'add_new_item' => __( 'Add New League', 'nsl-sports-league-ml' ),
		'new_item_name' => __( 'New League Name', 'nsl-sports-league-ml' ),
		'separate_items_with_commas' => __( 'Separate League with commas', 'nsl-sports-league-ml' ),
		'add_or_remove_items' => __( 'Add or remove League', 'nsl-sports-league-ml' ),
		'choose_from_most_used' => __( 'Choose from the most used League', 'nsl-sports-league-ml' ),
		'menu_name' => __( 'League', 'nsl-sports-league-ml' ),
	); 
	register_taxonomy('nsl-league-category','nsl-sports-team',array(
		'hierarchical' => false,
		'labels' => $labels,
		'supports' => array( 'title', 'thumbnail'  ),
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'update_count_callback' => '_update_post_term_count',
		'publicly_queryable'  => false,
		'query_var' => false,
		'rewrite' => array( 'slug' => 'nsl-league-category' ),
	));
}
add_action( 'init', 'nsl_spt_league_nonhierarchical_taxonomy', 0 );
/* adding thumbanail image support for league category */
/********************************************************/
/* this is for when you add the category directly */ 
function nsl_spt_league_add_term_image($taxonomy){ ?>
	<div class="form-field term-group">
		<p><?php  _e("Upload an Image","nsl-sports-league-ml"); ?></p>
		<div class="nls-img-box"></div>
		<input type="hidden" name="txt_upload_image" id="txt_upload_image" value="" style="width: 77%">
		<input type="button" id="upload_image_btn" class="button button-primary button-large" value="<?php  _e("Featured image","nsl-sports-league-ml"); ?>" />
	</div>
	<?php
}
add_action('nsl-league-category_add_form_fields', 'nsl_spt_league_add_term_image', 10, 2);
function nsl_spt_league_save_term_image($term_id, $tt_id) {
	if (isset($_POST['txt_upload_image']) ){
		$group = sanitize_text_field($_POST['txt_upload_image']);
		add_term_meta($term_id, 'term_image', $group, true);
	}
}
add_action('created_nsl-league-category', 'nsl_spt_league_save_term_image', 10, 2);
/* this is for when you edit the category*/
function nsl_spt_league_edit_image_upload($term, $taxonomy) {
	$txt_upload_image = get_term_meta($term->term_id, 'term_image', true); ?>
		<div class="form-field term-group">
			<p><?php  _e("Upload an Image","nsl-sports-league-ml"); ?></p>
			<div class="nls-img-box">
				<?php
					echo ( ! empty( trim( $txt_upload_image ) ) ) ? 
					"<div>
						<p><a href='javascript:;' class='nls-rmv-img' > ". __("Remove image")." </a></p>
						<img src='$txt_upload_image' width='250' height='250' />
					</div>" : ""; 
				?>
			</div>
			<input type="hidden" name="txt_upload_image" id="txt_upload_image" value="<?php echo $txt_upload_image ?>" style="width: 77%">
			<input type="button" id="upload_image_btn" class="button button-primary button-large" value="<?php  _e("Featured image","nsl-sports-league-ml"); ?>" />
		</div>
	<?php
}
add_action('nsl-league-category_edit_form_fields', 'nsl_spt_league_edit_image_upload', 10 , 2);
function nsl_spt_league_update_image_upload($term_id, $tt_id) {
	if (isset($_POST['txt_upload_image']) ){
		$group = sanitize_text_field($_POST['txt_upload_image']);
		update_term_meta($term_id, 'term_image', $group);
	}
}
add_action('edited_nsl-league-category', 'nsl_spt_league_update_image_upload', 10, 2);
/* renaming the excerpt label in nsl-sports-team post type  */
function nsl_changExcerpt_name_link_excerpt_name($translation, $original){
	if (get_post_type( ) == 'nsl-sports-team') {
		if ('Excerpt' == $original) return __('Team Nickname', "nsl-sports-league-ml");
		else {
			$pos = strpos($original, 'Excerpts are optional hand-crafted summaries of your');
			if ($pos !== false) 
				return 'Add any video link in the box above';
		}
	}
	return $translation;
}
add_filter('gettext', 'nsl_changExcerpt_name_link_excerpt_name', 10, 2 );
