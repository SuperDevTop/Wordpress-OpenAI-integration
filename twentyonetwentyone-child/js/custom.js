jQuery( document ).ready( function( $ ) {
    // Add your custom button click event handler
    $( '#generate' ).on( 'click', function( e ) {
        e.preventDefault();

        // Retrieve the post content
        var postContent = $( '#ai_generate' ).val();

        // Make the AJAX request to your server endpoint
        $.ajax( {
            url: twentytwentyone_child_ajax.ajaxurl,
            type: 'POST',
            data: {
                action: 'twentytwentyone_child_openai_request',
                post_content: postContent,
            },
            beforeSend: function() {
                // Display loading indicator or disable the button if needed
            },
            success: function( response ) {
                // Handle the successful response
                $( '#ai_generate' ).val( response.data );

                // Clear loading indicator or enable the button if needed
            },
            error: function( xhr, textStatus, errorThrown ) {
                // Handle the error response
            },
            complete: function() {
                // Clear loading indicator or enable the button if needed
            }
        } );
    } );
} );
