<a class="button-secondary" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=yawpp">< <?php _e("Retour", 'yawpp'); ?></a><br /><br />

<?php

//Suppression d'un signataire
if(isset($_POST['deletesign'])){
	delete_sign();
}


//Exportation au format Excel

//if ( isset($_POST['export_xls'])) {
//	export_signs($_GET['id']);
//}

global $wpdb;
$p = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_petitions WHERE id = %d", $_GET['id']), OBJECT);
?>

<div id="icon-users" class="icon32"></div><h3><?php _e("Signataires de la pétition", 'yawpp'); ?> "<?php echo $p[0]->title; ?>"</h3><br /><br />
<form method="post" id="download_form" action="">
<input type="submit" name="export_xls" class="button-primary" value="<?php _e("Exporter au format XLS", 'yawpp'); ?>" />
</form>
<br />
<?php
//On récupère la liste des signataires pour la pétition

$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_signs WHERE id_petition = %d", $_GET['id']), OBJECT);

    if($result) {    
    
    
    	//S'il y a des signataires, on affiche les champs dans le tableau   
        echo "<table class='widefat'>
        <thead>
        <tr>";
        
        
        //Affichage des entêtes du tableau
        $resultfields = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_fields WHERE id_petition = %d", $_GET['id']), OBJECT);
        
        foreach($resultfields as $rf){
        	echo "<th>".$rf->text."</th>";        
        }
        
        echo "<th>".__("Supprimer", 'yawpp')."</th>
        	</tr>
       		</thead>
        	<tfoot>    
       	 	<tr>";
        
        foreach($resultfields as $rf){
        	echo "<th>".$rf->text."</th>";        
        }
        
        echo "<th>".__("Supprimer", 'yawpp')."</th>
        </tr>
        </tfoot>";
        
        foreach($result as $r)
        {
                echo "<tbody><tr>";
                
                //On désérialise les champs
                $fieldsvalue = unserialize($r->fieldstable);
                //On boucle sur les champs
                foreach($resultfields as $rf){
	                echo "<td>".$fieldsvalue[$rf->id]."</td>";
	            }
                echo '</td>';
                echo '<td><form method="post"><input type="hidden" name="id_sign" value="'.$r->id.'" /><input type="submit" name="deletesign" value="'.__("Supprimer", 'yawpp').'" class="button-secondary" /></form></td></tr></tbody>';
        }
        echo "</table>";
        
	}else{
        echo "<h3>".__("Aucun signataire pour cette pétition.", 'yawpp')."</h3>";
    }

?>
</div>
<br /><br />

<a class="button-secondary" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=yawpp">< <?php _e("Retour", 'yawpp'); ?></a>
