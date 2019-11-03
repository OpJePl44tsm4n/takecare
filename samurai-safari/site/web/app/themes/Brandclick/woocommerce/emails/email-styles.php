<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load colors.
$bg        = get_option( 'woocommerce_email_background_color' );
$body      = get_option( 'woocommerce_email_body_background_color' );
$base      = get_option( 'woocommerce_email_base_color' );
$base_text = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text      = get_option( 'woocommerce_email_text_color' );

// Pick a contrasting color for links.
$link_color = wc_hex_is_light( $base ) ? $base : $base_text;

if ( wc_hex_is_light( $body ) ) {
	$link_color = wc_hex_is_light( $base ) ? $base_text : $base;
}

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $text, 20 );
$base_lighter_40 = wc_hex_lighter( $text, 40 );
$base_lighter_85 = wc_hex_lighter( $text, 95 );
$text_lighter_80 = wc_hex_lighter( $text, 80 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
?>



#wrapper {
	background-color: <?php echo esc_attr( $bg ); ?>;
	margin: 0;
	padding: 46px 0 0 0;
	-webkit-text-size-adjust: none !important;
	width: 100%;
}

#template_header_image {
	text-align: left;
	width: 100vw;
    max-width: 630px;
    padding-bottom: 1em;
}

#template_header_image img {
	padding: 0 20px;
	margin-bottom: 0;
	max-width: 140px;
}

#template_container {
	background-color: <?php echo esc_attr( $body ); ?>;
	border: none;
	max-width: 100vw;
}

#template_body {
	max-width: 100vw;
}

.card.header {
    margin-bottom: 3em;
    min-height: 215px;
}   

.card.header img {
    max-width: 40%;
    float: right;
}

.card.header .content {
	display: flex;
    -ms-flex-align: center;
    align-items: center;
    max-width: 60%;
}
        
.card.header h1 {
    margin: 0;
    text-align: left;
    color: <?php echo esc_attr( $base ); ?>;
}

#body_content .card.header p {
	margin: 0 0 16px;
}

a.btn-primary {
    color: <?php echo esc_attr( $body ); ?>;
    background: <?php echo esc_attr( $base ); ?>;
    padding: .8em 1em;
    border-radius: 4px; 
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

a.btn-primary:hover {
    opacity: .6;
}

@media only screen and (max-width: 600px) {
    .card.header {
        text-align: center !important;
    }
    .card.header img {
        float: none !important;
        max-width: 100% !important;
        margin: auto !important;
        max-height: 250px;
    }	
    
    .card.header h1, .card.header p {
        text-align: center !important;
    }

    .card.header .content {
		max-width: 100% !important; 
		height: auto !important;
	}
}

#template_body {
	padding: 1em 0 2em;
}

tr.order_item td {
    width: 25%;
    text-align: right !important;
}

tr.order_item td:first-child {
    width: 50%;
    text-align: left !important;
}

#order_item_totals {
    font-size: 15px;
    font-weight: 500;
}

#order_item_totals tr th {
    font-weight: 400;
}

#order_item_totals tr:last-child,
#order_item_totals tr:last-child th {
	font-size: 16px;
	font-weight: 900;
}

#order_item_totals small.includes_tax {
    display: none;
}

.card {
	background: <?php echo esc_attr( $base_lighter_85 ); ?>;
	padding: 0 25px;
	padding: 25px 25px 1px;
    margin-bottom: 2em;
}

#footer_call_to_action td {
	background: <?php echo esc_attr( $base_lighter_85 ); ?>;
	padding: 25px 25px 15px;
	font-family: "Encode Sans Narrow", Helvetica, Roboto, Arial, sans-serif;
	line-height: 1.5;
	max-width: 100vw;
}

#footer_call_to_action td h2 + p {
	margin-top: 0;
	font-size:16px;
	line-height:1.5
}

#footer_row {
	background: <?php echo esc_attr( $text ); ?>;
	padding-bottom: 50px;
}

@media only screen and (max-width: 600px) {
  	#footer_row .mobile_full_width {width:100%!important; padding:0;}	
 
}

#footer_row h2,
#footer_row h3,
#footer_row p,
#footer_row a {
	color: <?php echo esc_attr( $body ); ?>; 
	font-family: "Encode Sans Narrow", Helvetica, Roboto, Arial, sans-serif;
}

#footer_row h3 {
	margin-bottom: 0;	
}

#footer_row p {
	margin: 0;
	font-size: 14px;
    line-height: 1.5em;
}

#footer_row a {
	white-space: nowrap;
}

#body_content {
	background-color: <?php echo esc_attr( $body ); ?>;
	font-size: 18px;
    line-height: 1.5;
}

#body_content table td:not(.border-row) {
	border: none !important;
}

#body_content table .border-row {
	padding-top: 10px; 
	border-top: 2px solid <?php echo esc_attr( $text_lighter_80 ); ?>;
}

#body_content table .order_total td {
	max-width: 100px;
}

#body_content table {
	table-layout: fixed;
	max-width: 100vw;
}

#body_content table .woocommerce-Price-amount {
	font-weight: 900;
}

#body_content table .includes_tax {
	font-size: 11px;
}

#body_content td ul.wc-item-meta {
	font-size: small;
	margin: 1em 0 0;
	padding: 0;
	list-style: none;
}

#body_content td ul.wc-item-meta li {
	margin: 0.5em 0 0;
	padding: 0;
}

#body_content td ul.wc-item-meta li p {
	margin: 0;
}

#body_content p {
	margin: 0 0 30px;
	font-size: 16px;
    line-height: 1.5;
}

#body_content_inner {
	color: <?php echo esc_attr( $text ); ?>;
	font-family: "Encode Sans Narrow", Helvetica, Roboto, Arial, sans-serif;
	font-size: 14px;
	line-height: 150%;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

#body_content_inner > p {
	padding: 0 25px;
}

.td {
	color: <?php echo esc_attr( $text ); ?>;
	border: none;
	vertical-align: middle;
}

#addresses {
	padding: 0 25px !important;
}

.address {
	padding: 0;
	font-style: initial;
	color: <?php echo esc_attr( $text ); ?>;
}

.text {
	color: <?php echo esc_attr( $text ); ?>;
	font-family: "Encode Sans Narrow", Helvetica, Roboto, Arial, sans-serif;
}

.link {
	color: <?php echo esc_attr( $base ); ?>;
}

#header_wrapper {
	padding: 36px 48px;
	display: block;
}

h1 {
	font-family: "Encode Sans Narrow", Helvetica, Roboto, Arial, sans-serif;
	font-size: 30px;
	font-weight: 900;
	line-height: 150%;
	padding: 0 0 8px;
}

h2 {
	color: <?php echo esc_attr( $text ); ?>;
	display: block;
	font-family: "Encode Sans Narrow", Helvetica, Roboto, Arial, sans-serif;
	font-size: 18px;
	font-weight: bold;
	line-height: 130%;
	margin: 0 0 10px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

h3 {
	color: <?php echo esc_attr( $text ); ?>;
	display: block;
	font-family: "Encode Sans Narrow", Helvetica, Roboto, Arial, sans-serif;
	font-size: 16px;
	font-weight: bold;
	line-height: 130%;
	margin: 16px 0 8px;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

a {
	color: <?php echo esc_attr( $link_color ); ?>;
	font-weight: normal;
	text-decoration: underline;
}

img {
	border: none;
	display: inline-block;
	font-size: 14px;
	font-weight: bold;
	height: auto;
	outline: none;
	text-decoration: none;
	text-transform: capitalize;
	vertical-align: middle;
}
<?php
