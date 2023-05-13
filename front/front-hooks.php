<?php 
/**
 * Enqueing js/css for front end.
 *
 * @package WordPress
 **/

function nsl_enqueue_Front_script_style(){
	wp_enqueue_style( 'nls_front-style', plugin_dir_url( __FILE__ ). '/css/front-style.css'  );
	wp_register_script( 'nsl_front_script', plugin_dir_url( __FILE__ ). '/js/front-script.js' , array('jquery'), false, true );
	wp_localize_script( 'nsl_front_script', 'jsData',[
		'ajaxUrl' => admin_url('admin-ajax.php'),
	]);
	wp_enqueue_script('nsl_front_script');
}
add_action("wp_enqueue_scripts", "nsl_enqueue_Front_script_style");
/* development for ajax pagination */
function nsl_paginateWithAJAXFun(){
	// echo "<pre>"; print_r( $_POST ); echo "</pre>"; exit ; // for debuggin....
	ob_start();
	$qry_args = array(
		"post_type"     => "nsl-sports-team",
		"posts_per_page"=> $_POST["posts_no_per_page"],
		"post_status"   => "publish",
		'paged' => $_POST["next_page_no"]
	);
	if( $_POST['searched_qry'] != 'false' )
		$qry_args["s"] = $_POST['searched_qry'];
	if( $_POST["category_id"] != 'false'  ){
		$qry_args["tax_query"] = array(
			array (
				'taxonomy' => 'nsl-league-category',
				'field' => 'id',
				'terms' => $_POST["category_id"],
			)
		);
	}
	// echo "<pre>"; print_r( $qry_args ); echo "</pre>";
	$team_qry = new WP_Query( $qry_args );
	if( $team_qry->have_posts() ):
		while( $team_qry->have_posts() ): $team_qry->the_post();
			$img_url = get_the_post_thumbnail_url( get_the_ID(), "full" ); ?>
			<div class="list-box" >
				<div class="img-box">
					<?php echo  ( $img_url ) ? "<img src='$img_url' alt='post-image' class=post-img' width='250' height='250' >" : ""; ?>
				</div>
				<div class='nsl-team-info' >
					<h3 class="list-title" ><?php the_title(); ?></h3>
					<p class="nsl-nickname" ><?php echo __("Nickname: ","nsl-sports-league-ml") . wp_strip_all_tags( get_the_excerpt() ); ?></p>
				</div>
			</div>
		<?php endwhile; wp_reset_postdata();
	else:
		echo "<h4 class='nsl-no-posts-found'>" . __("No post found!", "nsl-sports-league-ml" ) . "</h4>";
	endif;
	$response["html_template"] = ob_get_clean();
	wp_send_json( $response );
}
add_action("wp_ajax_nopriv_nsl_paginateWithAJAX","nsl_paginateWithAJAXFun");
add_action("wp_ajax_nsl_paginateWithAJAX","nsl_paginateWithAJAXFun");
