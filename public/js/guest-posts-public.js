(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
    $( document ).ready(function (){

        $( '#guest-post' ).submit(function (e) {
            e.preventDefault();
            var ext = $( '#file' ).val().split('.').pop().toLowerCase();

            if ( ! $( '#post_title' ).val()) {
                $( '#post_title' ).css( 'border-color', 'red' );
                window.scrollTo( { top: 0, behavior: 'smooth' } );
            }
            if ( ! $( '#custom_post_type' ).val()) {
                $( '#custom_post_type' ).css( 'border-color', 'red' );
                window.scrollTo( { top: 0, behavior: 'smooth' } );
            }
            if ($( '#post_content' ).val() === '') {
                $( '#wp-post_content-editor-container' ).css( 'border-color', 'red' );
            }
            if ( ! $( '#post_excerpt' ).val()) {
                $( '#post_excerpt' ).css( 'border-color', 'red' );
            }
            if ($( '#file' ).get(0).files.length === 0) {
                $( '.error' ).css( 'display','block' );
            } else {
                if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                    $( '.error1' ).css( 'display', 'block' );
                }
            }

            if ($( '#post_title' ).val() &&
                $( '#custom_post_type' ).val() &&
                $( '#post_excerpt' ).val() &&
                $( '#file' ).get(0).files.length != 0 &&
                $( '#post_content' ).val() != '' &&
                $.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) != -1) {

                    var file = jQuery(document).find( 'input[type="file"]' );
                    var individual_file = file[0].files[0];

                    var form_data = new FormData();
                    form_data.append( 'action', 'guest_posts_form_submit' );
                    form_data.append( "post_type", $('#custom_post_type').val() );
                    form_data.append( "post_title", $('#post_title').val() );
                    form_data.append( "post_content", $('#post_content').val() );
                    form_data.append( "post_excerpt", $('#post_excerpt').val() );
                    form_data.append( "postImg", individual_file );

                    jQuery.ajax({
                        url: guestObj.ajax_url,
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: form_data,
                        beforeSend: function () {
                            $( '#loader' ).show();
                        },
                        success: function (data) {
                            $( '.success_msg' ).show();
                            $( '.success_msg' ).delay(2000).fadeOut();
                            document.getElementById( 'guest-post' ).reset();

                        },
                        complete: function (data) {
                            $( '#loader' ).hide();
                            window.scrollTo({ top: 0, behavior: 'smooth' });

                        },
                    })
            }
        });
        $( 'select, input' ).change(function () {
            $(this).css( 'border', '1px solid' );
            if ($( '#file' ).get(0).files.length != 0) {
                $( '.error' ).css( 'display','none' );
                $( '.error1' ).css( 'display','none' );
            }
        });
        $( 'textarea' ).on("change keyup paste", function() {
            $(this).css('border', '1px solid');
        });
        $( '#wp-post_content-editor-container' ).bind('DOMSubtreeModified', function () {
            $(this).css('border', '1px solid');
        });
    });

})( jQuery );
