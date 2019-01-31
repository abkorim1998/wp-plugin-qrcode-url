<?php
/*
Plugin name: Showing URL in QR Code
Plugin URI: http://abkorim.com/
Description: Reade you website on your phpone by scanig QR code
Author: abkorim
Author URI: http://abkorim.com
Text Domain: get_sit_on_phone
Domain Path: /languages/
Version: 0.0.1
*/

defined( 'ABSPATH' ) or die( 'it\'s not possibol' );

class kbqrCodeClass 
{
	public function __construct() {

		//>>>>>>>>>>>>>>>>>>>>>>|| WP ENQUEUE SCRIPTS || <<<<<<<<<<<<<<<<
		add_action( 'wp_enqueue_scripts', array($this, 'urlinqrcoed_wp_enqueue_function') );

		//>>>>>>>>>>>>>>>>>>>>>>|| ADMIN ENQUEUE SCRIPTS || <<<<<<<<<<<<<<<<
		add_action( 'admin_enqueue_scripts', array($this, 'urlinqrcoed_adin_area_enqueue_function') );

		//>>>>>>>>>>>>>>>>>>>>>>|| INIT FUNCTION || <<<<<<<<<<<<<<<<
		add_action("init", array($this, "urlinqrcoed_admin_init_func"));

		//>>>>>>>>>>>>>>>>>>>>>>|| DO WHENE ACTIVATE THIS PLUGIN || <<<<<<<<<<<<<<<<
		register_activation_hook( __FILE__, array($this, 'urlinqrcoed_mkbplugin_activate') );

		//>>>>>>>>>>>>>>>>>>>>>>|| DO WHENE DEACTIVATE THIS PLUGIN || <<<<<<<<<<<<<<<<
		register_deactivation_hook(__FILE__, array($this,"urlinqrcoed_mkbplugin_deactivation"));

		
		

		
		

		global $wpdb;
		$table_name = $wpdb->prefix . "kbqurcode_url";
		$apply_info = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = 1" );

		//>>>>>>>>>>>>>>>>>>>>>>|| SHWING ON POSTS || <<<<<<<<<<<<<<<<
		if ( $apply_info->posts == "on" ) {
			
			add_filter( 'the_content', array($this, 'urlinqrcoed_for_posts') );
	
		}
		
		//>>>>>>>>>>>>>>>>>>>>>>|| SHWING ON PAGE || <<<<<<<<<<<<<<<<
		if ( $apply_info->pages == "on" ) {

			add_filter( 'the_content', array($this, 'urlinqrcoed_for_pages') );
	
		}
	


	}//end of "__construct"


	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| WP ENQUEUE SCRIPTS || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	public function urlinqrcoed_wp_enqueue_function()
	{
		wp_enqueue_script( 'qrcode_min_js', plugins_url('js/qrcode.min.js', __FILE__),  array( "jquery" ) );
		wp_enqueue_script( 'kb_qrcode_custom_js', plugins_url('js/kb_custom.js', __FILE__),  array( "jquery" ) );
		wp_enqueue_style( "page_qrcode_style", plugins_url("css/style.css", __FILE__) );
	}


	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| ADMIN ENQUEUE SCRIPTS || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	public function urlinqrcoed_adin_area_enqueue_function()
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
	public function urlinqrcoed_admin_init_func(){

		//>>>>>>>>>>>>>>>>>>>>>>|| MENU PAGE || <<<<<<<<<<<<<<<<
		if ( current_user_can('edit_pages') ){
			add_action('admin_menu', array($this, 'urlinqrcoed_code_menu'));
		}

	}




	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| DO WHENE ACTIVATE THIS PLUGIN || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	public function urlinqrcoed_mkbplugin_activate() {

		global $wpdb;

		$table_name = $wpdb->prefix . "kbqurcode_url";

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
			'colorDark' => '000000',
			'colorLight' => 'ffffff', 
			'bg_color' => '829082', 
		));

	}
	

	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| DO WHENE DEACTIVATE THIS PLUGIN || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	public function urlinqrcoed_mkbplugin_deactivation()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . "kbqurcode_url";
		$wpdb->query( "DROP TABLE IF EXISTS $table_name ");
	}



	
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| MENU PAGE || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	public function urlinqrcoed_code_menu() {

		add_menu_page(
			'open site on phone',
			'QR CODE', 'read', 
			'my-unique-qr-code', 
			array($this, 'urlinqrcoed_menu_callback_func'), //MENU PAGE CALLBACK FUNCTIONS
			'dashicons-smiley', 
		25);
		
	}
	


	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| MENU PAGE CALLBACK FUNCTIONS || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	public function urlinqrcoed_menu_callback_func(){

		global $wpdb;
		$table_name = $wpdb->prefix . "kbqurcode_url";
		
		if(isset($_POST['urlinqrcoed-submit'])){
			
			$pages = sanitize_text_field($_POST['checkbox-nested-1']);
			$posts = sanitize_text_field($_POST['checkbox-nested-2']);
			
			$colorDark 		= str_replace( '#', '', $_POST['colorDark'] );
			$colorLight 	= str_replace( '#', '', $_POST['colorLight']);
			$width 			= filter_var( $_POST['width'], FILTER_VALIDATE_INT );
			$height 		= filter_var( $_POST['height'], FILTER_VALIDATE_INT );
			$bg_color 		= str_replace( '#', '', $_POST['bg_color']);
		
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
					<input type="color" name="bg_color" id="bg_color" value="#<?php echo $kb_qr_code_info->bg_color; ?>">

					<!-- bg color -->
					<label for="colorDark">Select colorDark</label>
					<input type="color" name="colorDark" id="colorDark" value="#<?php echo $kb_qr_code_info->colorDark; ?>">
					
					<!-- bg color -->
					<label for="colorLight">Select colorLight</label>
					<input type="color" name="colorLight" id="colorLight" value="#<?php echo $kb_qr_code_info->colorLight; ?>">

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

			<input id="kbsubmit" type="submit" name="urlinqrcoed-submit" value="Save">
		</form>
		
		
		<?php
	
		
	}

	


	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| SHWING ON POSTS || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	public function urlinqrcoed_for_posts( $content ) {
			
		global $wpdb;
		$table_name = $wpdb->prefix . "kbqurcode_url";
		$apply_info = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = 1" );

		
		if( is_single() ) {
		$page_url = get_the_permalink();
		$content .= '<div class="kb_qrcode_div">
						<div class="bgleayr" style="background: '.'#'.$apply_info->bg_color.';opacity:0.9;">
							<div id="qrcode_imag_div"></div>
						</div>
					</div>
					<script type="text/javascript">
						new QRCode(document.getElementById("qrcode_imag_div"), {
							text: "'.$page_url.'",
							width: "'.$apply_info->width.'",
							height: "'.$apply_info->height.'",
							colorDark : "'.'#'.$apply_info->colorDark.'",
							colorLight : "'.'#'.$apply_info->colorLight.'",
							correctLevel : QRCode.CorrectLevel.H
						});
					</script>';
		

		}
		return $content;
		
	}
	

	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| SHWING ON PAGE || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	public function urlinqrcoed_for_pages( $content ) {
				
		global $wpdb;
		$table_name = $wpdb->prefix . "kbqurcode_url";
		$apply_info = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = 1" );
		
		if( is_page() ) {
		$page_url = get_the_permalink();
		$content .= '<div class="kb_qrcode_div">
						<div class="bgleayr" style="background: '.'#'.$apply_info->bg_color.';opacity:0.9;">
							<div id="qrcode_imag_div"></div>
						</div>
					</div>
					<script type="text/javascript">
						new QRCode(document.getElementById("qrcode_imag_div"), {
							text: "'.$page_url.'",
							width: "'.$apply_info->width.'",
							height: "'.$apply_info->height.'",
							colorDark : "'.'#'.$apply_info->colorDark.'",
							colorLight : "'.'#'.$apply_info->colorLight.'",
							correctLevel : QRCode.CorrectLevel.H
						});
					</script>';
		

		}
		return $content;
	
	}

	


	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/
	//>>>>>>>>>>>>>>>>>>>>>>|| SHORD CODE || <<<<<<<<<<<<<<<<
	//****************_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*******************/

	// add_shortcode("kb_qr_code","kb_qr_shortcode_fun");
	// function kb_qr_shortcode_fun(){
	//  ob_start() 
		
	// 	<!-- image div
	// 	<div class="kb_qrcode_div">
	// 		<div class="bgleayr ">
	// 			<div id="qrcode_imag_div"></div>
	// 		</div>
	// 	</div>
		
	// 	functionality
	// 	<script type="text/javascript">
	// 		new QRCode(document.getElementById("qrcode_imag_div"), {
	// 			text: "abkori.com",
	// 			width: 200,
	// 			height: 200,
	// 			colorDark : "#000",
	// 			colorLight : "#fff",
	// 			correctLevel : QRCode.CorrectLevel.H
	// 		});
	// 	</script> -->

		

	// 	 <?php
	// 	 return ob_get_clean();
	// }








} //"kaqrCodeClass()" class ended


//instantiate kaqrCodeClass();
$kbqrCodeClass = new kbqrCodeClass();




























