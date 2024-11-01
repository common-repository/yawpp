<?php


/*-------------------------------------
Vérification de l'unicité d'un champs
--------------------------------------*/


//Affichage de la liste des signataires
function check_unicity($id, $field, $id_petition){

global $wpdb;

//On récupère la liste des signataires pour la pétition

$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_signs WHERE id_petition = %d", $id_petition), OBJECT);

    if($result) {
    
        //On récupère les champs
        $resultfields = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_fields WHERE id_petition = %d", $id_petition), OBJECT);
        
        foreach($result as $r)
        {                
                //On désérialise les champs
                $fieldsvalue = unserialize($r->fieldstable);
               	if ($fieldsvalue[$id] == $field) {
               		return false;
               	}
               	else{
               		return true;
               	}
                
        }
        
        
	}else{
		return true;
        
    }
  

}



/*----------------------------------------
Ajout d'une signature à la base de donnée
-----------------------------------------*/

function add_signs(){


global $wpdb;

//On vérifie que tous les champs obigatoires ont été remplis

//On récupère l'ID de la pétition
$id_petition = $_POST['id'];

// On récupère les champs dans la base de donnée.
$fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_fields WHERE id_petition = %d", $id_petition), OBJECT);

//On boucle sur les champs
$test = 0;
$wrongmail = 0;
$unique = 0;
foreach($fields as $field){

	if($field->needed == 1){
		if(empty($_POST['field'.$field->id])){
			$test++;
		}
		else{
			if($field->type == "email"){
				if(!filter_var($_POST['field'.$field->id], FILTER_VALIDATE_EMAIL)){
					$wrongmail++;					
				}
			}
			
			if($field->uniquefield == 1){
			
				if(!check_unicity($field->id, $_POST['field'.$field->id], $id_petition)){
					$unique++;
				}
				
			}
		}
	}

}



if($test == 0){

	if($wrongmail == 0){
	
		if($unique == 0){
	
		//Sérialisation des valeurs des champs
		$fieldstable[] = NULL;
		foreach($fields as $field){
			if(empty($_POST['field'.$field->id])){
				$fieldstable[$field->id] = '';
			}else{
				$fieldstable[$field->id] = $_POST['field'.$field->id];
			}
		}
		
		$serializefields = serialize($fieldstable);	
		
		$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."yawpp_signs (time, fieldstable, display, id_petition) VALUES ( now(), %s, 1, %d)", $serializefields,  $id_petition));
	
		echo '<div class="okmessage_yawpp">'.__("Merci, votre signature a bien été enregistrée.", 'yawpp').'</div>';	
	 }else{
	 	echo '<div class="errormessage_yawpp">'.__("Vous avez déjà signé cette pétition.", 'yawpp').'</div>';
	 }
	
	}else{
		echo '<div class="errormessage_yawpp">'.__("Merci d'enter un e-mail valide.", 'yawpp').'</div>';
	}
	
}else{

	echo '<div class="errormessage_yawpp">'.__("Veuillez remplir tous les champs obligatoires.", 'yawpp').'</div>';
}


}


?>
