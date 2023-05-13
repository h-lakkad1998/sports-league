jQuery(document).ready(function($){
    console.log("media js loaded!");
    var meta_image_frame;
    $('#upload_image_btn').click(function(e){
        e.preventDefault();
        if ( meta_image_frame ) {
            meta_image_frame.open();
            return;
        }
        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            library: { type: 'image' }
        });
        meta_image_frame.on('select', function(){
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
            console.log(media_attachment);
            $('#txt_upload_image').val(media_attachment.url);
            $(".nls-img-box").html( 
                `<div>
                    <p><a href='javascript:;' class='nls-rmv-img' > ${ meta_image.button } </a></p>
                    <img src=${media_attachment.url} width='250' height='250' />
                </div>` 
            );
        });
        meta_image_frame.open();
    });
    $(document).on('click', '.nls-rmv-img' ,function () {
        $(this).parents(".nls-img-box").html(""); 
        $( "#txt_upload_image" ).val("");
    });
});