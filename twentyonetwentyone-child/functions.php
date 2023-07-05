<?php

	function add_custom_meta_box() {
    add_meta_box(
        'custom-meta-box',
        'Custom Meta Box',
        'render_custom_meta_box',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_box');

function render_custom_meta_box($post) {
	    $content = get_post_field('post_content', $post->ID);
    echo '<div class="container d-flex justify-content-center" style="text-align:center"><button type="button" id="generate" class="button button-primary">Generate OpenAI Response</button>';
    echo '<p></p>';
	echo '<textarea name="ai_generate" id="ai_generate" rows="10" cols="80">' . esc_textarea($content) . '</textarea></div>';

}

// Enqueue your custom JavaScript file
    function twentytwentyone_child_enqueue_scripts() {
        wp_enqueue_script( 'twentytwentyone-child-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ), '1.0', true );
        wp_localize_script( 'twentytwentyone-child-scripts', 'twentytwentyone_child_ajax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ) );
    }
    add_action( 'add_meta_boxes', 'twentytwentyone_child_enqueue_scripts' );
    // add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_scripts' );


    // Create an AJAX endpoint for your OpenAI API request
    function twentytwentyone_child_openai_request() {
        // Retrieve necessary data from the AJAX request
        $post_content = $_POST['post_content'];

        // Make your OpenAI API request here
        // Replace 'YOUR_API_KEY' with your actual OpenAI API key
        // Replace 'YOUR_MODEL_ID' with the ID of the OpenAI model you want to use
        $api_key = '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';
        $model_id = 'text-davinci-003';
        // $api_endpoint = 'https://api.openai.com/v1/engines/' . $model_id . '/completions';
        // $api_endpoint = 'https://api.openai.com/v1/completions';
        // $headers = array(
        //     'Content-Type: application/json',
        //     'Authorization: Bearer ' . $api_key,
        // );

        // $data = array(
        //     'prompt' => $post_content,
        //     'max_tokens' => 100,
        //     'model' => $model_id,
        // );

        // // Send the API request
        // $response = wp_remote_post( $api_endpoint, array(
        //     'headers' => $headers,
        //     'body'    => json_encode( $data ),
        // ) );

        // if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
        //     $body = wp_remote_retrieve_body( $response );
        //     $response_data = json_decode( $body, true );

        //     // Retrieve the generated text from the OpenAI response
        //     $generated_text = $response_data['choices'][0]['text'];

        //     // Send the generated text back as the AJAX response
        //     wp_send_json_success( $generated_text );
        // } else {
        //     // Handle API request error
        //     wp_send_json_error( 'API request failed.' );
        // }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.openai.com/v1/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "model": "text-davinci-003",
                "prompt": "'.$post_content.'",
                "temperature": 0.3,
                "max_tokens": 100,
                "top_p": 1.0,
                "frequency_penalty": 0.0,
                "presence_penalty": 0.0
                }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $api_key,
                'Content-Type: application/json;'
            ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
    
        $result = json_decode($response);
        $text=preg_replace("@\n@","",$result->choices[0]->text);

        wp_send_json_success( $generated_text );

        // Ensure proper termination of the script
        wp_die();
    }
    add_action( 'wp_ajax_twentytwentyone_child_openai_request', 'twentytwentyone_child_openai_request' );
    add_action( 'wp_ajax_nopriv_twentytwentyone_child_openai_request', 'twentytwentyone_child_openai_request' );
        
?>