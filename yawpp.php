<?php 
/*
Plugin Name: Yawpp (Yet Another Wordpress Plugin Petition)
Plugin URI: http://www.ostenta.fr/yawpp/
Description: A simple plugin that allows you to create highly customisable petition and add petition's forms and signatories to your pages or posts
Author: Sebastien Le Gall
Version: 1.2.2
Author URI: http://www.ostenta.fr/
Text Domain: yawpp
Domain Path: /lang
*/



/*-----------------------------------------------------------------------
						Activation du plugin
-------------------------------------------------------------------------*/
register_activation_hook( __FILE__, 'yawpp_install' );

//Installation de la base de la table dans la base de donnée
function yawpp_install() {
	global $wpdb;
   
   	//table des pétitions
   	$table_petition = $wpdb->prefix . "yawpp_petitions";
   	$table_fields = $wpdb->prefix . "yawpp_fields";
   	$table_signs = $wpdb->prefix . "yawpp_signs";
  	
   	
   	$sql = "CREATE TABLE ".$table_petition." (
  	id mediumint(9) NOT NULL AUTO_INCREMENT,
  	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  	title varchar(300),
  	enable tinyint(1) NOT NULL,
  	max smallint(9) NOT NULL,
  	confirmation_email text,
  	UNIQUE KEY id (id)
  	);
  	CREATE TABLE ".$table_signs." (
  	id mediumint(9) NOT NULL AUTO_INCREMENT,
  	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  	fieldstable text,
  	display tinyint(1) NOT NULL,
  	id_petition mediumint(9) NOT NULL,
  	UNIQUE KEY id (id)
  	);
  	CREATE TABLE ".$table_fields." (
  	id mediumint(9) NOT NULL AUTO_INCREMENT,
  	text varchar(100),
  	type enum('text','email','comment','checkbox') NOT NULL,
  	id_petition mediumint(9) NOT NULL,
  	needed tinyint(1) NOT NULL,
  	uniquefield tinyint(1) NOT NULL,
  	private tinyint(1) NOT NULL,
  	UNIQUE KEY id (id)
  	)";
  	
   	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   	dbDelta( $sql );
   	
}

function export_xls() {
    if (isset($_POST['export_xls']) && $_GET['section'] == 'showsigns' ) {    
			export_signs($_GET['id']);
			exit;
			
	}
	register_setting( 'export_xls', 'export_xls' );
}





/*-----------------------------------------------------------------------
			Définition de la page principale d'administation
-------------------------------------------------------------------------*/

load_plugin_textdomain('yawpp', false, basename( dirname( __FILE__ ) ) . '/lang' );

//Ajout de la page d'administration au menu
function add_settings_page() {
add_menu_page( "Yawpp (Yet Another Wordpress Petition Plugin)", "Yawpp",'edit_posts',  'yawpp', 'yawpp_admin', plugins_url('img/icon_yawpp.png', __FILE__) );
}

/*----------
   Action
------------*/
//Ajout d'un menu
add_action( 'admin_menu', 'add_settings_page' );
//Chargement du css et scripts
add_action('admin_head', 'admin_register_head');
    
add_action( 'wp_enqueue_scripts', 'add_stylesheet' );

add_action( 'admin_init', 'export_xls' );

function add_stylesheet() {
        wp_enqueue_style( '', plugins_url('displaystyle.css', __FILE__) );
    }


//Ajout de la feuille de style et des scripts JS
function admin_register_head() {
   $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/yawpp.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
?>
	<script type="text/javascript">
		function add_fields(i) {
			var i2 = i + 1;
			document.getElementById('field_'+i).innerHTML = '<div class="yawppfield"><label for="name"><?php _e("Nom", 'yawpp'); ?> : </label><input type="text" id="name" name="name['+i+']" />&nbsp;&nbsp;&nbsp;<label for="type"><?php _e("Type de champs", 'yawpp'); ?> : </label><select name="type['+i+']" id="type"><option value="text"><?php _e("Texte", 'yawpp'); ?></option><option value="email"><?php _e("Email", 'yawpp'); ?></option><option value="comment"><?php _e("Commentaire", 'yawpp'); ?></option><option value="checkbox"><?php _e("Case à cocher", 'yawpp'); ?></option></select>&nbsp;&nbsp;&nbsp;<label for="needed"><?php _e("Obligatoire", 'yawpp'); ?> </label><input type="checkbox" id="needed" name="needed['+i+']" value="1" checked />&nbsp;&nbsp;&nbsp;<label for="private"><?php _e("Privé", 'yawpp'); ?> </label><input type="checkbox" id="private" name="private['+i+']" value="1" />&nbsp;&nbsp;&nbsp;<label for="unique"><?php _e("Unique", 'yawpp'); ?> </label><input type="checkbox" id="unique" name="unique['+i+']" value="1" /></div></div><br />';
			
			document.getElementById('field_'+i).innerHTML += (i <= 10) ? '<br /><div id="field_'+i2+'"><a href="javascript:add_fields('+i2+')" class="button-secondary"><?php _e("Ajouter un champs", 'yawpp'); ?></a></div>' : '';
}
</script>
<?php
}

/*----------
  Définition
------------*/
function yawpp_admin(){

$siteurl = get_option('siteurl');
$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/';
?>

<div class="wrap">
<h2><img src="<?php echo $url; ?>img/yawpp.png" alt="Logo YAWPP" class="logo"/>
YAWPP (Yet Anoter Wordpress Petition Plugin)</h2>
<br /><br />

<?php

if(isset($_GET['section']))
{

	if($_GET['section'] == 'showsigns'){
		if(isset($_GET['id'])){
			include (plugin_dir_path( __FILE__ ). '/admin_signs.php');
		}
	}
	
	if($_GET['section'] == 'update'){
		if(isset($_GET['id'])){
			include (plugin_dir_path( __FILE__ ). '/admin_update.php');
		}
	}
	
	if($_GET['section'] == 'help'){
		include (plugin_dir_path( __FILE__ ). '/help.php');
	}


}
else{
	include (plugin_dir_path( __FILE__ ). '/admin_main.php');
}
?>
<br /><br /><br /><div class="footeryawpp"><small><?php _e("Ce plugin est développé et maintenu par", 'yawpp'); ?> <a href="http://www.ostenta.fr">Sébastien Le Gall</a></small></div>

<?php
}



/*------------------------
 Fonctions d'administration
--------------------------*/
include (plugin_dir_path( __FILE__ ). '/admin_functions.php');

/*------------------------
 Fonctions d'affichage
--------------------------*/
include (plugin_dir_path( __FILE__ ). '/display_yawpp.php');


/*------------------
   Filtres
--------------------*/
add_filter('the_content', 'petition_parse');
add_filter('the_content', 'signs_parse');
add_filter('the_content', 'num_signs_parse');

function petition_parse( $content )
{
	// Parse
	$content = preg_replace_callback("/\[yawpp-form-[0-9]*\]/i", "yawpp_form", $content);

	return $content;
}

function yawpp_form($tag){

	foreach($tag as $t){
		preg_match("/\[yawpp-form-([0-9]*)\]/i", $t, $match);
		return yawpp_display_form($match[1]);
	}
}

function signs_parse( $content )
{
	// Parse
	$content = preg_replace_callback("/\[yawpp-signs-[0-9]*\]/i", "yawpp_signs", $content);

	return $content;
}

function yawpp_signs($tag){

	foreach($tag as $t){
		preg_match("/\[yawpp-signs-([0-9]*)\]/i", $t, $match);
		return yawpp_display_signs($match[1]);
	}
}

function num_signs_parse( $content )
{
	// Parse
	$content = preg_replace_callback("/\[yawpp-num-signs-[0-9]*\]/i", "yawpp_num_signs", $content);

	return $content;
}

function yawpp_num_signs($tag){

	foreach($tag as $t){
		preg_match("/\[yawpp-num-signs-([0-9]*)\]/i", $t, $match);
		return yawpp_display_num_signs($match[1]);
	}
}


?>
