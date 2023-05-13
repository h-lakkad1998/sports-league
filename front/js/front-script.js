jQuery(document).ready(function ($) {
    $('body').on("click",".nsl_load_more_button", function () {
        var category_attr = ( $(this).attr( 'data-nsl-category' ) ) ? $(this).attr( 'data-nsl-category' ) : false;
        var this_btn = $(this);
        this_btn.hide();
        var max_num_pages =  this_btn.attr( 'data-max-pages' );
        var posts_pagination = this_btn.attr( 'data-posts-pagination' );
        var next_page = this_btn.attr( 'data-next-page' );
        var searched_var = ( this_btn.attr( 'data-searched' ) ) ? this_btn.attr( 'data-searched' ) : false;
        var action_name = this_btn.attr( 'data-ajax-action' );
        this_btn.parents(".nsl_ajax_pagination").find(".loading-btn").show();
        $.ajax({
            type: "post",
            url: jsData.ajaxUrl,
            data: {
                action :  action_name,
                posts_no_per_page : posts_pagination,
                next_page_no : next_page,
                searched_qry  : searched_var,
                category_id : category_attr
            },
            success: function (response) {
                if( next_page >=  max_num_pages ){
                    $(".nsl_load_more_button").hide();
                }else{
                    $(".nsl_load_more_button").show();
                }
                this_btn.parents(".elementor-widget-container").find('.nsl-sports-container').append( response.html_template );
                this_btn.parents(".nsl_ajax_pagination").find(".nsl_load_more_button").attr( 'data-next-page', ++next_page );
                this_btn.parents(".nsl_ajax_pagination").find(".loading-btn").hide();
            }
        });
    });
    if( $(".nsl-searchbox-available").length > 0 ){
        $('body').on('keypress','.nsl-searchbox-available .nsl-search-box',function(e) {
            if(e.which == 13) {
                const parser = new URL(window.location.href || window.location);
                parser.searchParams.set( $(this).attr("name") , $(this).val());
                window.location.href = parser.href;
            }
        });
    }
});