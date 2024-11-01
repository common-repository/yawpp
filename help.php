<a class="button-secondary" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=yawpp">< <?php _e("Retour", 'yawpp'); ?></a><br /><br />
<div class="help">
<h3><?php _e("Comment insérer une pétition dans un article ou une page?", 'yawpp'); ?></h3>
<p><?php _e("Chaque pétition est composée de trois éléments", 'yawpp'); ?> : 
<ul>
	<li><?php _e("Le formulaire permettant aux utilisateurs de signer", 'yawpp'); ?></li>
	<li><?php _e("La liste des signataires", 'yawpp'); ?></li>
	<li><?php _e("Le nombre de signataires pour une pétition", 'yawpp'); ?></li>
</ul>

<?php _e("A chacune de ces parties correspond un shortcode à insérer dans le texte de la page ou de l'article.", 'yawpp'); ?><br />
<?php _e("Ainsi, si l'on souhaite ajouter le formulaire de signature, mais pas la liste des signataires, il suffit de n'insérer que le shortcode correspondant au formulaire.", 'yawpp'); ?></p>

<p><?php _e("Les shortcodes s'écrivent comme ceci", 'yawpp'); ?>:<br />
<ul>
	<li><pre>[yawpp-form-<i>"ID de la pétition"</i>]</pre> <?php _e("pour le formulaire", 'yawpp'); ?></li>
	<li><pre>[yawpp-signs-<i>"ID de la pétition"</i>]</pre> <?php _e("pour la liste des signataires", 'yawpp'); ?></li>
	<li><pre>[yawpp-num-signs-<i>"ID de la pétition"</i>]</pre> <?php _e("pour le nombre de signataires", 'yawpp'); ?></li>
</ul>
<i>"<?php _e("ID de la pétition", 'yawpp'); ?>"</i> <?php _e("correspond au numéro unique attribué à chaque pétition, présenté dans la première colone du tableau d'administration du plugin", 'yawpp'); ?></p>


<h3><?php _e("Comment modifier les styles de la pétition?", 'yawpp'); ?></h3>

<p><?php _e("Les style CSS concernant l'affichage de la pétition dans le thème Wordpress sont contenus dans le fichier displaystyle.css du répertoire du plugin (/wp-content/plugins/yawpp)", 'yawpp'); ?></p>

</div>
<br /><br />
<a class="button-secondary" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=yawpp">< <?php _e("Retour", 'yawpp'); ?></a>
