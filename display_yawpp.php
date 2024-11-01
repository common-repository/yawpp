<?php

include(plugin_dir_path( __FILE__ ). '/display_functions.php');


//Affichage du formulaire de la pétition
function yawpp_display_form($id){

	if(isset($_POST['submit_yawpp'])){
	
		add_signs();
	
	}

	global $wpdb;
	
	//variable de retour
	$html = '';
	
	//On récupère les champs de la pétition
	$fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_fields WHERE id_petition = %d", $id), OBJECT);
	
	$html .= '<div class="form_yawpp"><fieldset><form name="form_yawpp" method="post">';
	
	foreach ($fields as $field){
		$html .= '<div class="field_yawpp"><label for="field'.$field->id.'">'.$field->text;
		if($field->needed == 0){
			$html .= ' : </label>';
		}else{
			$html .= '* : </label>';
		}
		
		switch($field->type){
			case 'text':
				$html .='<input type="text" name="field'.$field->id.'" id="field'.$field->id.'" />';
				break;
			
			case 'email':
				$html .='<input type="email" name="field'.$field->id.'" id="field'.$field->id.'" />';
				break;
			
			case 'comment':
				$html .='<textarea name="field'.$field->id.'" id="field'.$field->id.'"></textarea>';
				break;
			case 'checkbox':
				$html .= '<input type="checkbox" name="field'.$field->id.'" id="field'.$field->id.'" value="1" />';
		}
		$html .='<br /></div>';
		
	}
	
	$html .= '<p>* '.__("Champs obligatoires", 'yawpp').'</p><input type="hidden" name="id" value="'.$id.'" /><input type="submit" value="Valider" name="submit_yawpp" /></fieldset></form></div>';
	
	return $html;		
	
}

//Affichage de la liste des signataires
function yawpp_display_signs($id){

global $wpdb;

$html = null;

//On récupère la liste des signataires pour la pétition

$html .= '<div class="signs_yawpp">';

$p = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_petitions WHERE id = %d", $id), OBJECT);

if(empty($p[0]->max)){
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_signs WHERE id_petition = %d ORDER BY id DESC", $id), OBJECT);
}
else{
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_signs WHERE id_petition = %d ORDER BY id DESC LIMIT 0, %d", $id, $p[0]->max), OBJECT);
}

    if($result) {
    
    	//S'il y a des signataires, on affiche les champs dans le tableau   
        $html .= "<table class='signs_table'>
        <thead>
        <tr>";
        
        
        //Affichage des entêtes du tableau
        $resultfields = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_fields WHERE id_petition = %d", $id), OBJECT);
        
        foreach($resultfields as $rf){
        	if ($rf->private != 1) $html .= "<th>".$rf->text."</th>";        
        }
        
        $html .=  "
        	</tr>
       		</thead>
        	<tfoot>    
       	 	<tr>";
        
        foreach($resultfields as $rf){
        	if ($rf->private != 1) $html .=  "<th>".$rf->text."</th>";        
        }
        
        $html .=  "
        </tr>
        </tfoot>";
        
        foreach($result as $r)
        {
                $html .=  "<tbody><tr>";
                
                //On désérialise les champs
                $fieldsvalue = unserialize($r->fieldstable);
                //On boucle sur les champs
                foreach($resultfields as $rf){
	                if ($rf->private != 1)  $html .=  "<td>".$fieldsvalue[$rf->id]."</td>";
	            }
                $html .=  '</td>';
               $html .=  "<td></td></tr></tbody>";
        }
        $html .=  "</table>";
        
	}else{
        $html .=  "<h3>".__("Aucun signataire pour cette pétition.", 'yawpp')."</h3>";
    }
    
    $html .=  '</div>';
    
    
	return $html    ;

}


//Affichage du nombre de signataire

function yawpp_display_num_signs($id){

global $wpdb;
	$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_signs WHERE id_petition = %d ORDER BY id DESC", $id), OBJECT);
	
	return '<span class="yawppnumsign">'.$wpdb->num_rows.'</span>';



}




?>
