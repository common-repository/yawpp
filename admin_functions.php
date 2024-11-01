<?php

/*---------------------------------------
   Ajout d'une pétition à la base de donnée
--------------------------------------------*/
function add_petition(){
global $wpdb;

$message = NULL;

//vérification des champs
if(empty($_POST['title']))
{
	$message = "".__("Vous devez obligatoirement rentrer un tire", 'yawpp')."<br />";
	$title = false;
}
else{
	$title= true;
}

/*if(empty($_POST['mail']))
{
	$message .= "Vous devez obligatoirement rentrer un message pour l'e-mail de confirmation";
	$mail = false;
}
else{
	$mail= true;
}*/

$return = 0;
foreach($_POST['name'] as $n){

	if(empty($n)){
		$return++;
	}
}

//Si tout est OK, on insert dans la base de donnée.
if($title && $return == 0){
	$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."yawpp_petitions (title, max, confirmation_email, enable, time) VALUES ( %s, %d, %s, %d, now())", $_POST['title'], $_POST['max'], ' ', $_POST['enable']));
	
	$lastid = $wpdb->insert_id;
	for($i=0; $i<count($_POST['name']); $i++){
		if(isset($_POST['needed'][$i])){
			$needed = 1;
		}
		else{
		
			$needed = 0;
		}
	
		if(isset($_POST['private'][$i])){
			$private = 1;
		}
		else{
			$private = 0;
		}
		
		if(isset($_POST['unique'][$i])){
			$unique = 1;
		}
		else{
			$unique = 0;
		}
		

		$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."yawpp_fields (text, type, id_petition, needed, private, uniquefield) VALUES ( %s, %s, %d, %d, %d, %d)", $_POST['name'][$i], $_POST['type'][$i], $lastid, $needed, $private, $unique));
		
		
		
	}
	
}

else{
	$message .= "".__("Vous devez entrer un nom pour chaque champs de la pétition", 'yawpp')."";
	echo '<div class="error">'.$message.'</div>';
}

}


/*---------------------------------------
   Suppression d'une pétition à la base de donnée
--------------------------------------------*/
function delete_petition(){

global $wpdb;

$id_petition = $_POST['id_petition'];

//On supprime la pétition...
$wpdb->delete( $wpdb->prefix."yawpp_petitions", array( 'id' => $id_petition ) );
//Les champs associé...
$wpdb->delete( $wpdb->prefix."yawpp_fields", array( 'id_petition' => $id_petition ) );
//et les signataires associés...
$wpdb->delete( $wpdb->prefix."yawpp_signs", array( 'id_petition' => $id_petition ) );


echo '<div class="updated">'.__("La pétition a bien été supprimée", 'yawpp').'</div>';

}





/*---------------------------------------
   Désactivation d'une pétition
--------------------------------------------*/

function disable_petition(){

global $wpdb;

$id_petition = $_POST['id_petition'];

$wpdb->update($wpdb->prefix."yawpp_petitions", array( 'enable' => 0 ), array('id' => $id_petition));

}

/*---------------------------------------
   activation d'une pétition
--------------------------------------------*/

function enable_petition(){

global $wpdb;

$id_petition = $_POST['id_petition'];

$wpdb->update($wpdb->prefix."yawpp_petitions", array( 'enable' => 1 ), array('id' => $id_petition));

}


/*---------------------------------------
   Mise à jour d'une pétition
--------------------------------------------*/
function update_petition(){
global $wpdb;

$message = NULL;

//vérification des champs
if(empty($_POST['title']))
{
	$message = "".__("Vous devez obligatoirement rentrer un tire", 'yawpp')."<br />";
	$title = false;
}
else{
	$title= true;
}

/*if(empty($_POST['mail']))
{
	$message .= "".__("Vous devez obligatoirement rentrer un message pour l'e-mail de confirmation";
	$mail = false;
}
else{
	$mail= true;
}
*/

$return = 0;

foreach($_POST['nameupdate'] as $n){

	if(empty($n)){
		$return++;
	}
}

if(isset($_POST['name'])){
	foreach($_POST['name'] as $n){

		if(empty($n)){
			$return++;
		}
	}
}



//Si tout est OK, on met à jour la base de donnée.
if($title && $return == 0){

	//Mise à jour de la pétition
	$wpdb->update($wpdb->prefix."yawpp_petitions", array('title' => $_POST['title'], 'max'=>$_POST['max']), array('id'=>$_GET['id']));
	
	//Mise à jour des champs
	for($i=0; $i<count($_POST['nameupdate']); $i++){
		
		if(isset($_POST['neededupdate'][$i])){
			$needed = 1;
		}
		else{
		
			$needed = 0;
		}
	
		if(isset($_POST['privateupdate'][$i])){
			$private = 1;
		}
		else{
			$private = 0;
		}
		
		if(isset($_POST['uniqueupdate'][$i])){
			$unique = 1;
		}
		else{
			$unique = 0;
		}
	
		
			$wpdb->update($wpdb->prefix."yawpp_fields", array('text' => $_POST['nameupdate'][$i], 'type'=>$_POST['typeupdate'][$i], 'needed'=>$needed, 'private'=>$private, 'uniquefield'=>$unique), array('id'=> $_POST['id'][$i]));
	
	}
	
	//Ajout des nouveaux champs
	if(isset($_POST['name'])){
		for($i=1; $i<=count($_POST['name']); $i++){
			if(isset($_POST['needed'][$i])){
			$needed = 1;
		}
		else{
		
			$needed = 0;
		}
	
		if(isset($_POST['private'][$i])){
			$private = 1;
		}
		else{
			$private = 0;
		}
		
		if(isset($_POST['unique'][$i])){
			$unique = 1;
		}
		else{
			$unique = 0;
		}
		

		$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."yawpp_fields (text, type, id_petition, needed, private, uniquefield) VALUES ( %s, %s, %d, %d, %d, %d)", $_POST['name'][$i], $_POST['type'][$i], $lastid, $needed, $private, $unique));
		
		}
	}
	
	
	echo '<div class="updated">'.__("La pétition a été mise à jour", 'yawpp').'</div>';
}

else{
	$message .= __("Vous devez entrer un nom pour chaque champs de la pétition", 'yawpp');
	echo '<div class="error">'.$message.'</div>';
}

}


/*---------------------------------------
   Suppression d'un signataire
--------------------------------------------*/
function delete_sign(){

global $wpdb;

$id_sign = $_POST['id_sign'];


//et les signataires associés...
$wpdb->delete( $wpdb->prefix."yawpp_signs", array( 'id' => $id_sign ) );


echo '<div class="updated">'.__("La signature bien été supprimée", 'yawpp').'</div>';

}

/*---------------------------------------------------------
	Export de la liste des signataires au format Excel
----------------------------------------------------------- */

function export_signs($id){

	global $wpdb;
	$time = date("Y-m-d");
    header("Content-Type: application/vnd.ms-excel");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("content-disposition: attachment;filename=yawpp-$time.xls");
    
    //On récupère la liste des signataires pour la pétition

	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_signs WHERE id_petition = %d", $id), OBJECT);

    if($result) {    
    
    
    	//S'il y a des signataires, on affiche les champs dans le tableau   
        echo "<table>
        <thead>
        <tr>";
         
        //Affichage des entêtes du tableau
        $resultfields = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_fields WHERE id_petition = %d", $id), OBJECT);
        
        foreach($resultfields as $rf){
        	echo "<th>".$rf->text."</th>";        
        }
        
        echo "</tr>
       		</thead>";
        
        foreach($result as $r)
        {
                echo "<tbody><tr>";
                
                //On désérialise les champs
                $fieldsvalue = unserialize($r->fieldstable);
                //On boucle sur les champs
                foreach($resultfields as $rf){
	                echo "<td>".$fieldsvalue[$rf->id]."</td>";
	            }
                
        }
        echo "</tr></table>";
	}
}



?>
