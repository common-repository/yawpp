<?php

//Mise à jour d'une pétition
if(isset($_POST['update'])){
	update_petition();
}
?>

<a class="button-secondary" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=yawpp">< <?php _e("Retour", 'yawpp'); ?></a><br /><br />
<div class="updatepetition">
<div id="icon-edit" class="icon32"></div><h3><?php _e("Modifier une pétition", 'yawpp'); ?></h3><br /><br />
<?php

//On récupère les valeurs de champs de la pétition

global $wpdb;
$query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_petitions WHERE id = %d", $_GET['id']);
$p = $wpdb->get_results($query, OBJECT);

$petition = $p[0];


//Affichage du formulaire de création d'une nouvelle pétition
?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" name="updatepetion">
<ul>
	<li><label for="title"><?php _e("Titre", 'yawpp'); ?> : </label>
		<input id="title" name="title" value="<?php echo $petition->title; ?>"  maxlength="200" size="50" /></li>
	<li><label for="max"><?php _e("Nombre maximum de signatures à afficher", 'yawpp'); ?> : </label>
		<input id="max" name="max" value="<?php echo $petition->max; ?>" maxlength="6" size="4" /><small>(<?php _e("Laisser vide pour ne pas mettre de limite", 'yawpp'); ?>)</small></li>
	<!--<li><label for="mail">Message de l'e-mail de confirmation : <br /></label>
		<textarea id="mail" name="mail" rows="15" cols="60"><?php echo $petition->confirmation_email; ?></textarea></li>-->
</ul>

<h4><?php _e("Champs de la pétition", 'yawpp'); ?></h4>

<p><?php _e("Modifiez ou Ajoutez les champs que les utilisateurs devront remplir pour signer la pétition.", 'yawpp'); ?><br />
<small><i><?php _e("Exemple: Nom, Prénom, e-mail, commentaire, profession, etc.", 'yawpp'); ?></i></small></p>

<?php

//On récupère les champs dans la base de donnée

$fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."yawpp_fields WHERE id_petition = %d", $_GET['id']), OBJECT);

$i=0;
foreach($fields as $f){
?>

<div class="yawppfield">
<label for="name"><?php _e("Nom", 'yawpp'); ?> : </label><input type="text" id="name" name="nameupdate[<?php echo $i; ?>]"  value="<?php echo $f->text ?>"/>&nbsp;&nbsp;&nbsp;
<label for="type"><?php _e("Type de champs", 'yawpp'); ?> : </label>
	<select name="typeupdate[<?php echo $i; ?>]" id="type">
		<option value="text" <?php if($f->type == "text") echo "selected"; ?> ><?php _e("Texte", 'yawpp'); ?></option>
		<option value="email" <?php if($f->type == "email") echo "selected"; ?> ><?php _e("Email", 'yawpp'); ?></option>
		<option value="comment" <?php if($f->type == "comment") echo "selected"; ?> ><?php _e("Commentaire", 'yawpp'); ?></option>
		<option value="checkbox" <?php if($f->type == "checkbox") echo "selected"; ?> ><?php _e("Case à cocher", 'yawpp'); ?></option>
	</select>
<input type="hidden" name="id[<?php echo $i; ?>]" value="<?php echo $f->id; ?>" />
&nbsp;&nbsp;&nbsp;<label for="needed"><?php _e("Obligatoire", 'yawpp'); ?> </label><input type="checkbox" id="needed" name="neededupdate[<?php echo $i; ?>]" value="1" <?php if($f->needed == 1) echo "checked"; ?> />
&nbsp;&nbsp;&nbsp;<label for="private"><?php _e("Privé", 'yawpp'); ?> </label><input type="checkbox" id="private" name="privateupdate[<?php echo $i; ?>]" value="1" <?php if($f->private == 1) echo "checked"; ?> />
&nbsp;&nbsp;&nbsp;<label for="unique"><?php _e("Unique", 'yawpp'); ?> </label><input type="checkbox" id="unique" name="uniqueupdate[<?php echo $i; ?>]" value="1" <?php if($f->uniquefield == 1) echo "checked"; ?> />
	
</div>

<?php
$i++;
}
?>

<br />
<div id="field_1"><a href="javascript:add_fields(1)" class="button-secondary"><?php _e("Ajouter un champs", 'yawpp'); ?></a></div>
<br />
<input type="submit" value="<?php _e("Mettre à jour", 'yawpp'); ?>" class="button-primary" name="update">
</form>

</div>
