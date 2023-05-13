<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 

class Elementor_NSL_League_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'nsl-sports-league-widget'; // widget slug name
	}
	public function get_title() {
		return esc_html__( 'NSL sports league', 'nsl-sports-league-ml' ); // widget title
	}
	public function get_icon() {
		return 'eicon-code'; //  widget icon
	}
	public function get_categories() {
		return [ 'general' ]; //  widget category
	}
	public function get_keywords() {
		return [ 'sports-league', 'nsl-sports-league', 'legues', 'nsl-sports-team' ]; // keyword to search teams
	}
	public function get_cat_array(){
		$terms_array['all'] = __('All', 'nsl-sports-league-ml' ) ; 
		$terms = get_terms( array(
			'taxonomy' => 'nsl-league-category',
			'hide_empty' => false,
		) );
		if( ! empty( $terms ) ){
			foreach( $terms as $single_term ){
				$terms_array[$single_term->term_id] =  $single_term->name;
			}
		}
		return $terms_array;
	}
	protected function register_controls() {
		// registering setting tab
		$this->start_controls_section(
			'settings_section',
			[
				'label' => esc_html__( 'Settings', 'nsl-sports-league-ml' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		// adding keywords text search box into the front
		$this->add_control(
			'nsl-key-words-on-of',
			[
				'label' => esc_html__( 'Show keyword searchbar?', 'nsl-sports-league-ml' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'nsl-sports-league-ml' ),
				'label_off' => esc_html__( 'Hide', 'nsl-sports-league-ml' ),
				'return_value' => 'show',
				'default' => 'hide',
			]
		);
		// adding select box field
		$this->add_control(
			'nsl-league-category',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'League Keword', 'nsl-sports-league-ml' ),
				'options' => $this->get_cat_array() ,
				'default' => 'all',
			]
		);
		// adding keywords number field for pagination
		$this->add_control(
			'nsl-post-per-pagination',
			[
				'label' => esc_html__( 'Posts per pagination', 'nsl-sports-league-ml' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'number',
				'default'   => 6
			]
		);
		// adding choices for pagination NORMAL OR AJAX
		$this->add_control(
			'nsl-sp-pageination-type',
			[
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'label' => esc_html__( 'Pagination type', 'nsl-sports-league-ml' ),
				'options' => [
					'normal' => [
						'title' => esc_html__( 'NORMAL', 'nsl-sports-league-ml' )
					],
					'ajax' => [
						'title' => esc_html__( 'AJAX', 'nsl-sports-league-ml' )
					]
				],
				'default' => 'normal',
			]
		);
		$this->end_controls_section();
		// adding style to the widget
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'nsl-sports-league-ml' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		// adding title colour
		$this->add_control(
			'nsl-league-title-color',
			[
				'label' => esc_html__( 'Title text colour', 'nsl-sports-league-ml' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#f00',
				'selectors' => [
					'{{WRAPPER}} h3.list-title' => 'color: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'nsl-league-title-size',
			[
				'label' => esc_html__( 'Title font size', 'nsl-sports-league-ml' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '15',
				'selectors' => [
					'{{WRAPPER}} h3.list-title' => 'font-size: {{VALUE}}px;',
				]
			]
		);
		$this->end_controls_section();
	}
	/* Generate output in the frontend */
	protected function render() {
		$settings = $this->get_settings_for_display();
		//echo "<pre>"; print_r( $settings ); echo "</pre>"; //for debuggin.....
		$settings = $this->get_settings_for_display();
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$posts_per_page = ($settings["nsl-post-per-pagination"]) ? $settings["nsl-post-per-pagination"] : 6;
		$searched_keywords = ( isset($_GET["nsl-search"]) && ! empty( trim( $_GET["nsl-search"] )  )  ) ? sanitize_text_field( $_GET["nsl-search"] ): false ; 
		$qry_args = array(
			"post_type"     => "nsl-sports-team",
			"posts_per_page"=> $posts_per_page,
			"post_status"   => "publish",
			'paged' => $paged
		);
		if( $searched_keywords ){
			$qry_args["s"] = $searched_keywords;
		}
		if( $settings["nsl-league-category"] !== "all" ){
			$qry_args["tax_query"] = array(
				array (
					'taxonomy' => 'nsl-league-category',
					'field' => 'id',
					'terms' => $settings["nsl-league-category"],
				)
			);
		}
		$team_qry = new WP_Query( $qry_args ); ?>
		<div class="nls-widget-template" >
			<?php
			if( $team_qry->have_posts() ): ?>
					<?php if( $settings["nsl-key-words-on-of"] == "show" ): ?>
						<div class="nsl-searchbox-available" >
							<input class='nsl-search-box' type="search" <?php echo ( $searched_keywords ) ? " value='$searched_keywords' " : ""; ?> name="nsl-search" placeholder="<?php _e("Search...",""); ?>">
						</div>
					<?php endif; ?>
				<div class="nsl-sports-container" >
					<?php while( $team_qry->have_posts() ): $team_qry->the_post();
						$img_url = get_the_post_thumbnail_url( get_the_ID(), "full" ); ?>
						<div class="list-box" >
							<div class="img-box">
								<?php echo  ( $img_url ) ? "<img src='$img_url' alt='post-image' class=post-img' width='250' height='250' >" : ""; ?>
							</div>
							<div class='nsl-team-info' >
								<h3 class="list-title" ><?php the_title();?></h3>
								<p class="nsl-nickname" ><?php echo __("Nickname: ","nsl-sports-league-ml") .      wp_strip_all_tags( get_the_excerpt() );   ?></p>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				<?php
				if( $team_qry->max_num_pages > 1 && $settings["nsl-sp-pageination-type"] === "normal" ):?>
					<div class="pagination">
						<?php 
							echo paginate_links( array(
								'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
								'total'        => $team_qry->max_num_pages,
								'current'      => max( 1, get_query_var( 'paged' ) ),
								'format'       => '?paged=%#%',
								'show_all'     => false,
								'type'         => 'plain',
								'end_size'     => 2,
								'mid_size'     => 1,
								'prev_next'    => true,
								'prev_text'    => sprintf( '<i></i> %1$s', __( '<<', 'text-domain' ) ),
								'next_text'    => sprintf( '%1$s <i></i>', __( '>>', 'text-domain' ) ),
								'add_args'     => false,
								'add_fragment' => '',
							) ); ?>
					</div>
				<?php elseif( $team_qry->max_num_pages > 1 && $settings["nsl-sp-pageination-type"] === "ajax" ):
						$filters_attributes = "";
						$filters_attributes .= (  $settings["nsl-league-category"] !== 'all' ) ? " data-nsl-category='" . $settings['nsl-league-category'] . "' " : "";
						$filters_attributes .=  " data-posts-pagination='" . $posts_per_page . "' ";
						$filters_attributes .= ( $searched_keywords !== false ) ? "          data-searched='$searched_keywords' ": "" ;
						$filters_attributes .= " data-max-pages='".$team_qry->max_num_pages."' ";
						$filters_attributes .= " data-next-page='2' ";
						$filters_attributes .= " data-ajax-action='nsl_paginateWithAJAX' ";   ?>
					<div class="nsl_ajax_pagination" >
						<div class="loading-btn" style="display: none;" >
							<img src="<?php echo home_url() . "/wp-includes/images/spinner-2x.gif" ?>" alt="loading-sppiner" >
						</div>
						<button <?php echo  $filters_attributes; ?> type="button" class="nsl_load_more_button" >
							<?php _e("Load More","nsl-sports-league-ml"); ?>
						</button>
					</div>
				<?php  endif;  wp_reset_postdata();
			else:
				echo "<h4 class='nsl-no-posts-found'>" . __("No post found!", "nsl-sports-league-ml" ) . "</h4>";
			endif; ?>
		</div>
		<?php
	}
}
