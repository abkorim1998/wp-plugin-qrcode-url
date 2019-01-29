<?php
/*
Plugin name: Get Site to Phone by QR Code
Plugin URI: http://abkorim.com/
Description: Reade you website on your phpone by scanig QR code
Author: abkorim
Author URI: http://abkorim.com
Text Domain: get_sit_on_phone
Domain Path: /languages/
Version: 0.0.1
*/


//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| WP ENQUEUE SCRIPTS || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
add_action( 'wp_enqueue_scripts', 'wp_enqueue_function' );
function wp_enqueue_function()
{
	wp_enqueue_script( 'qrcode_min_js', plugins_url('js/qrcode.min.js', __FILE__),  array( "jquery" ) );
	wp_enqueue_script( 'kb_qrcode_custom_js', plugins_url('js/kb_custom.js', __FILE__),  array( "jquery" ) );
	wp_enqueue_style( "page_qrcode_style", plugins_url("css/style.css", __FILE__) );
}



//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| ADMIN ENQUEUE SCRIPTS || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
add_action( 'admin_enqueue_scripts', 'adin_area_enqueue_function' );
function adin_area_enqueue_function()
{
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-checkboxradio' );
	wp_enqueue_script( 'jquery-ui-spinner' );
	wp_enqueue_script( 'qrcode_min_js', plugins_url('js/qrcode.min.js', __FILE__),  array( "jquery" ) );
	wp_enqueue_script( 'kb_qrcode_custom_js', plugins_url('js/kb_custom.js', __FILE__),  array( "jquery" ) );
	wp_enqueue_style( "page_qrcode_style", plugins_url("css/style.css", __FILE__) );
	
}


//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| INIT FUNCTION || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
add_action("init", "kb_admin_init_func");
function kb_admin_init_func(){
}




//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| DO WHENE ACTIVATE THIS PLUGIN || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
function mkbplugin_activate() {

	global $wpdb;

	$table_name = $wpdb->prefix . "mdabdulkorim";

	$charset_collate = $wpdb->get_charset_collate();
	$sql = " CREATE TABLE $table_name (
		id int(11) AUTO_INCREMENT,
		posts  varchar(255) ,
		pages  varchar(255) ,
		width  varchar(255) ,
		height varchar(255) ,
		colorDark varchar(255) ,
		colorLight varchar(255) ,
		bg_color varchar(255) ,
		PRIMARY KEY(id)
	) $charset_collate; ";


	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	$wpdb->insert( $table_name, array(
		'posts' => 'on',
		'width' => '200',
		'height' => '200',
		'colorDark' => '#000000',
		'colorLight' => '#ffffff', 
		'bg_color' => 'rgb(192, 192, 192)', 
	));

}
register_activation_hook( __FILE__, 'mkbplugin_activate' );







//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| DO WHENE DEACTIVATE THIS PLUGIN || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
function mkbplugin_deactivation()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "mdabdulkorim";
	$wpdb->query( "DROP TABLE IF EXISTS $table_name ");
}
register_deactivation_hook(__FILE__, "mkbplugin_deactivation");







//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| MENU PAGE || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
function qr_code_menu() {

	add_menu_page(
		'open site on phone',
		'QR CODE', 'read', 
		'my-unique-qr-code', 
		'qrcode_menu_func', 
		'dashicons-smiley', 
	25);
    
}
add_action('admin_menu', 'qr_code_menu');






//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| MENU PAGE CALLBACK FUNCTIONS || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
function qrcode_menu_func(){

	global $wpdb;
	$table_name = $wpdb->prefix . "mdabdulkorim";
	
	if(isset($_POST['submit'])){

		$pages = $_POST['checkbox-nested-1'];
		$posts = $_POST['checkbox-nested-2'];
		$colorDark = $_POST['colorDark'];
		$colorLight = $_POST['colorLight'];
		$width = $_POST['width'];
		$height = $_POST['height'];
		$bg_color = $_POST['bg_color'];
	
		$wpdb->replace($table_name, array( 

				'id' 			=> 1,
				'pages' 		=> $pages,
				'posts' 		=> $posts,
				'colorDark' 	=> $colorDark,
				'colorLight' 	=> $colorLight,
				'width' 		=> $width, 
				'height' 		=> $height,
				'bg_color'		=> $bg_color,
			)
			
		);

		
		
	}
	
	//get seting infor from database
	$kb_qr_code_info = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = 1" );
	

	?>

	<!-- <div class="header">
		<div class="header_overly">
			<h1>kb app</h1>
		</div>
	</div> -->

	<form action="" method="POST">
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Setting</a></li>
				<li><a href="#tabs-2">Styling</a></li>
				<li><a href="#tabs-3">about us</a></li>
			</ul>
			<div id="tabs-1">
				<h2>Show QR Code on</h2>
				<fieldset>

					<label for="checkbox-nested-1">pages
						<input type="checkbox" name="checkbox-nested-1" id="checkbox-nested-1" <?php if( $kb_qr_code_info->pages == "on" ) echo "checked";  ?>>
					</label>
					<label for="checkbox-nested-2">posts
						<input type="checkbox" name="checkbox-nested-2" id="checkbox-nested-2" <?php if( $kb_qr_code_info->posts == "on" ) echo "checked";  ?> >
					</label>
					
				</fieldset>
			</div>
			<div id="tabs-2">

				<h2>Content heading 3</h2>
				<!-- bg color -->
				<label for="bg_color">Select Background Color</label>
				<input type="color" name="bg_color" id="bg_color" value="<?php echo $kb_qr_code_info->bg_color; ?>">

				<!-- bg color -->
				<label for="colorDark">Select colorDark</label>
				<input type="color" name="colorDark" id="colorDark" value="<?php echo $kb_qr_code_info->colorDark; ?>">
				
				<!-- bg color -->
				<label for="colorLight">Select colorLight</label>
				<input type="color" name="colorLight" id="colorLight" value="<?php echo $kb_qr_code_info->colorLight; ?>">

				<!-- width -->
				<p>
					<label for="width">Select a width:</label>
					<input id="width" name="width" value="<?php echo $kb_qr_code_info->width; ?>">
				</p>
				<!-- height -->
				<p>
					<label for="height">Select a height:</label>
					<input id="height" name="height" value="<?php echo $kb_qr_code_info->height; ?>">
				</p>
 
 
			</div>
			<div id="tabs-3">
				<h2>Plugin Guids</h2>
				<p>This is a simple app, it helps you to transfer your site into your phone by QR code. </p>
			</div>
		</div>

		<input id="kbsubmit" type="submit" name="submit" value="Save">
	</form>
	
	
	<?php
   
   	
}




//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| SHWING ON POSTS || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
global $wpdb;
$table_name = $wpdb->prefix . "mdabdulkorim";
$apply_info = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = 1" );
if( $apply_info->posts == "on" ){

function kb_for_posts( $content ) {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "mdabdulkorim";
		$apply_info = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = 1" );
		if( is_single() ) {
		$page_url = get_the_permalink();
		$content .= '<div class="kb_qrcode_div">
						<div class="bgleayr" style="background: '.$apply_info->bg_color.';opacity:0.9;">
							<div id="qrcode_imag_div"></div>
						</div>
					</div>
					<script type="text/javascript">
						new QRCode(document.getElementById("qrcode_imag_div"), {
							text: "'.$page_url.'",
							width: "'.$apply_info->width.'",
							height: "'.$apply_info->height.'",
							colorDark : "'.$apply_info->colorDark.'",
							colorLight : "'.$apply_info->colorLight.'",
							correctLevel : QRCode.CorrectLevel.H
						});
					</script>';
		

		}
		return $content;


}
add_filter( 'the_content', 'kb_for_posts' );

}




//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| SHWING ON PAGE || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/

if( $apply_info->pages == "on" ){

function kb_for_pages( $content ) {
		
		global $wpdb;
		$table_name = $wpdb->prefix . "mdabdulkorim";
		$apply_info = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = 1" );

		if( is_page() ) {
		$page_url = get_the_permalink();
		$content .= '<div class="kb_qrcode_div">
						<div class="bgleayr" style="background: '.$apply_info->bg_color.';opacity:0.9;">
							<div id="qrcode_imag_div"></div>
						</div>
					</div>
					<script type="text/javascript">
						new QRCode(document.getElementById("qrcode_imag_div"), {
							text: "'.$page_url.'",
							width: "'.$apply_info->width.'",
							height: "'.$apply_info->height.'",
							colorDark : "'.$apply_info->colorDark.'",
							colorLight : "'.$apply_info->colorLight.'",
							correctLevel : QRCode.CorrectLevel.H
						});
					</script>';
		

		}
		return $content;
	


}
add_filter( 'the_content', 'kb_for_pages' );

}






//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
//>>>>>>>>>>>>>>>>>>>>>>|| SHORD CODE || <<<<<<<<<<<<<<<<
//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/

add_shortcode("kb_qr_code","kb_qr_shortcode_fun");
function kb_qr_shortcode_fun(){
	 ob_start() ?>
	
	<!-- image div -->
	<div class="kb_qrcode_div">
		<div class="bgleayr ">
			<div id="qrcode_imag_div"></div>
		</div>
	</div>
	
	<!-- functionality -->
	<script type="text/javascript">
		new QRCode(document.getElementById("qrcode_imag_div"), {
			text: "abkori.com",
			width: 200,
			height: 200,
			colorDark : "#000",
			colorLight : "#fff",
			correctLevel : QRCode.CorrectLevel.H
		});
	</script>

	

	 <?php
	 return ob_get_clean();
}













