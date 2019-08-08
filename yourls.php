<?php
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Change this to match the URL of your public interface. Something like: http://your-own-domain-here.com/index.php
$page = YOURLS_SITE . '/index.php' ;

// Part to be executed if FORM has been submitted
if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' ) {
    // Honeypot (The Greater Public)
    if ((yourls_is_valid_user() != 1) && (!empty($_REQUEST['keyword']))) {
        yourls_die($message = 'Only registered users are allowed to use custom keywords. Are you a bot?', $title = '', $header_code = 200);
    }

    // TESTING
    echo $_REQUEST['hv'];

	// Get parameters -- they will all be sanitized in yourls_add_new_link()
	$url     = $_REQUEST['url'];
	$keyword = isset( $_REQUEST['keyword'] ) ? $_REQUEST['keyword'] : '' ;
	$text    = isset( $_REQUEST['text'] ) ?  $_REQUEST['text'] : '' ;

	// Create short URL, receive array $return with various information
	$return  = yourls_add_new_link( $url, $keyword, $title );
	
	$shorturl = isset( $return['shorturl'] ) ? $return['shorturl'] : '';
	$message  = isset( $return['message'] ) ? $return['message'] : '';
	$status   = isset( $return['status'] ) ? $return['status'] : '';
	
	// Stop here if bookmarklet with a JSON callback function ("instant" bookmarklets)
	if( isset( $_GET['jsonp'] ) && $_GET['jsonp'] == 'yourls' ) {
		$short = $return['shorturl'] ? $return['shorturl'] : '';
		$message = "Short URL (Ctrl+C to copy)";
		header('Content-type: application/json');
		echo yourls_apply_filter( 'bookmarklet_jsonp', "yourls_callback({'short_url':'$short','message':'$message'});" );
		
		die();
	}
}

// Insert <head> markup and all CSS & JS files
yourls_html_head();

// Display title
echo "<h1>YOURLS - Your Own URL Shortener</h1>\n";

// Display left hand menu
yourls_html_menu() ;

// Part to be executed if FORM has been submitted
if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' ) {

	// Display result message of short link creation
	if( isset( $message ) ) {
		echo "<h2>$message</h2>";
	}
	
	if( $status == 'success' ) {
		// Include the Copy box and the Quick Share box
		yourls_share_box( $url, $shorturl, $title, $text );
		
		// Initialize clipboard -- requires js/share.js and js/clipboard.min.js to be properly loaded in the <head>
		echo "<script>init_clipboard();</script>\n";
	}

// Part to be executed when no form has been submitted
} else {

		$site = YOURLS_SITE;
		
		// Display the form
        echo <<<HTML
        <h2>Enter a new URL to shorten</h2>
		<form id="subForm" method="post" action="" autocomplete="off" onsubmit="fv19();">
		<p id="url"><label>URL: <input type="url" class="text" name="url" placeholder="http://www.domain.com" required></label></p>
        <p id="kw"><label>Optional custom short URL: $site/<input type="text" class="text" name="keyword"></label></p>
HTML;
        // Only registered users can use custom keywords
        if (yourls_is_valid_user() === true) {
            echo <<<HTML
            <style>
                #kw {
                    display: block !important;
                }
            </style>
HTML;
        } 

        echo <<<HTML
        <!-- MATH CAPTCHA -->
        <h2>Human Verification</h2>
        <label id="ebcaptchatext">What is x plus y?</label>
        <input type="text" class="text" id="ebcaptchainput" maxlength="2" required>
        <input type="text" name="hv">
        
        <p><input type="submit" class="button primary" value="Shorten" disabled></p>
        </form>
HTML;
}

?>

<!-- CUSTOM CSS -->
<style>
    input[name="url"]:invalid {
        border-color: red;
    }
    #kw {
        display: none;
    }
    #ebcaptchainput {
        width: 15px;
        text-align: center;
    }
    input#ebcaptchainput:invalid {
        border-color: red;
    }
    input[name="hv"] {
        display: none;
    }
    input[type="submit"] {
        background: lime;
        color: black;
    }
    input[type="submit"]:disabled {
        background: red;
        color: white;
    }
</style>

<!-- CUSTOM JAVASCRIPT -->
<script src="js/script.js"></script>

<!-- MATH CAPTCHA SCRIPT -->
<script src="js/ebcaptcha.js"></script>

<h2>Please note</h2>

<p>Be aware that a public interface <strong>will</strong> attract spammers. You are strongly advised to install anti spam plugins and any appropriate counter measure to deal with this issue.</p>

<span id="<?php echo md5(date('His'));?>"></span>

<?php

// Display page footer
yourls_html_footer();	