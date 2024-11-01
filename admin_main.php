<?php

//Validation du formulaire d'ajout d'une pétition
if(isset($_POST['addpetion'])){
	add_petition();
}

//Supression d'une pétition
if(isset($_POST['delete'])){
	delete_petition();
}


//Désactivation d'une pétition
if(isset($_POST['disable'])){
	disable_petition();
}

//Activation d'une pétition
if(isset($_POST['enablepetition'])){
	enable_petition();
}

?>

<div class="help">
<p><a href="<?php echo $_SERVER['REQUEST_URI'].'&section=help'; ?>"><?php _e("Comment insérer mes pétitions dans les pages et billets?", 'yawpp'); ?></a></p>
<p><a href="<?php echo $_SERVER['REQUEST_URI'].'&section=help'; ?>"><?php _e("Comment modifier les styles de la pétition?", 'yawpp'); ?></a></p>
</div><br /><br />


<div class="petitionlist">
<div id="icon-users" class="icon32"></div><h3><?php _e("Pétitions créés", 'yawpp'); ?></h3><br /><br />

<?php
//On récupère la liste des pétitions créés dans la base de donnée
global $wpdb;
$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."yawpp_petitions", OBJECT);

    if($result) {      
        echo "<table class='widefat'>
        <thead>    
        <tr>
        <th>ShortCodes</th>
        <th>".__("Titre", 'yawpp')."</th>
       	<th>".__("Date de création", 'yawpp')."</th>
        <th>".__("Maximum de signatures affichés", 'yawpp')."</th>
        <th>".__("Signataires", 'yawpp')."</th>
        <th>".__("Activer", 'yawpp')."</th>
        <th>".__("Action", 'yawpp')."</th>
        </tr>
        </thead>
        <tfoot>    
        <tr>
        <th>ShortCodes</th>
        <th>".__("Titre", 'yawpp')."</th>
       	<th>".__("Date de création", 'yawpp')."</th>
        <th>".__("Maximum de signatures affichés", 'yawpp')."</th>
        <th>".__("Signataires", 'yawpp')."</th>
        <th>".__("Activer", 'yawpp')."</th>
        <th>".__("Action", 'yawpp')."</th>
        </tr>
        </tfoot>";
        foreach($result as $r)
        {
                echo "<tbody><tr>";
                echo "<td>[yawpp-form-".$r->id."]<br />[yawpp-signs-".$r->id."]<br />[yawpp-num-signs-".$r->id."]</td>";
                echo "<td>".$r->title."</td>";
                echo "<td>".$r->time."</td>";
                echo "<td>".$r->max."</td>";
                echo '<td><a href="'.$_SERVER['REQUEST_URI'].'&section=showsigns&id='.$r->id.'" >'.__("Afficher les signataires", 'yawpp').'</a></td>';
                echo '<form method="post" action=""><input type="hidden" value="'.$r->id.'" name="id_petition" />';
                
                if($r->enable == 0)
                {
                	echo '<td><input type="submit" value="'.__("Activer", 'yawpp').'" name="enablepetition" class="button-secondary">&nbsp;';
                }
                else{
                	echo '<td><input type="submit" value="'.__("Désactiver", 'yawpp').'" name="disable" class="button-secondary">&nbsp;';
                }
                
                echo '</td><td><a href="'.$_SERVER['REQUEST_URI'].'&section=update&id='.$r->id.'" class="button-secondary">'.__("Modifier", 'yawpp').'</a>&nbsp;';
                echo '<input type="submit" value="'.__("Supprimer", 'yawpp').'" name="delete" class="button-secondary">&nbsp;';
                echo '</form></td>';
                echo "</tr></tbody>";
        }
        echo "</table>";
        
	}else{
        echo "<h3>".__("Aucune pétition n a été créé.", 'yawpp')."</h3>";
    }

?>
</div>
<br /><br />

<div class="newpetition">
<div id="icon-edit" class="icon32"></div><h3><?php _e("Créer une nouvelle pétition", 'yawpp'); ?></h3><br /><br />
<p><?php _e("Remplisser le formulaire ci-dessous pour créer une nouvelle pétition", 'yawpp'); ?></p>
<?php
//Affichage du formulaire de création d'une nouvelle pétition
?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" name="addpetion">
<ul>
	<li><label for="title"><?php _e("Titre", 'yawpp'); ?> : </label>
		<input id="title" name="title" value=""  maxlength="200" size="50" /></li>
	<li><label for="max"><?php _e("Nombre maximum de signatures à afficher", 'yawpp'); ?> : </label>
		<input id="max" name="max" value="" maxlength="6" size="4" /><small>(<?php _e("Laisser vide pour ne pas mettre de limite", 'yawpp'); ?>)</small></li>
	<!-- <li><label for="mail">Message de l'e-mail de confirmation : <br /></label>
		<textarea id="mail" name="mail" rows="15" cols="60"></textarea></li> -->
	<li><label for="enable"><?php _e("Activier la pétition", 'yawpp'); ?></label>
		<input type="checkbox" id="enable" name="enable" value="1" checked /></li>
</ul>

<h4><?php _e("Champs de la pétition", 'yawpp'); ?></h4>

<p><?php _e("Ajouter les champs que les utilisateurs devront remplir pour signer la pétition", 'yawpp'); ?>.<br />
<small><i><?php _e("Exemple: Nom, Prénom, e-mail, commentaire, profession, etc.", 'yawpp'); ?></i></small></p>

<div class="yawppfield">
<label for="name"><?php _e("Nom", 'yawpp'); ?> : </label><input type="text" id="name" name="name[0]" />&nbsp;&nbsp;&nbsp;
<label for="type"><?php _e("Type de champs", 'yawpp'); ?> : </label>
	<select name="type[0]" id="type">
		<option value="text"><?php _e("Texte", 'yawpp'); ?></option>
		<option value="email"><?php _e("Email", 'yawpp'); ?></option>
		<option value="comment"><?php _e("Commentaire", 'yawpp'); ?></option>
		<option value="checkbox"><?php _e("Case à cocher", 'yawpp'); ?></option>
	</select>
&nbsp;&nbsp;&nbsp;<label for="needed"><?php _e("Obligatoire", 'yawpp'); ?> </label><input type="checkbox" id="needed" name="needed[0]" value="1" checked />
&nbsp;&nbsp;&nbsp;<label for="private"><?php _e("Privé", 'yawpp'); ?> </label><input type="checkbox" id="private" name="private[0]" value="1" />
&nbsp;&nbsp;&nbsp;<label for="unique"><?php _e("Unique", 'yawpp'); ?> </label><input type="checkbox" id="unique" name="unique['0']" value="1" />
	
</div>
<br />
<div id="field_1"><a href="javascript:add_fields(1)" class="button-secondary"><?php _e("Ajouter un champs", 'yawpp'); ?></a></div>
<br />
<input type="submit" value="<?php _e("Valider", 'yawpp'); ?>" class="button-primary" name="addpetion">
</form>
</div>
</div>

