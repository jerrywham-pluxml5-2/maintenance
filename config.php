<?php
/**
 * Classe maintenance
 *
 * @version 1.1
 * @date	07/10/2013
 * @author	Cyril MAGUIRE
 **/
 
	if(!defined('PLX_ROOT')) exit; 
	
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
	
	if(!empty($_POST)) {
		$css = trim(str_replace(array(' ',"\n","\r","\t"),' ',(strip_tags($_POST['css']))));
		$plxPlugin->setParam('title', $_POST['title'], 'cdata');
		$plxPlugin->setParam('favicon', $_POST['favicon'], 'cdata');
		$plxPlugin->setParam('css', $css, 'cdata');
		$plxPlugin->setParam('html', $_POST['html'], 'cdata');
		$plxPlugin->saveParams();
		$plxPlugin->mkcss();
		$plxPlugin->mkhtml();
		header('Location: parametres_plugin.php?p=maintenance');
		exit;
	}
?>

<h2><?php $plxPlugin->lang('L_TITLE') ?></h2>
<p><?php $plxPlugin->lang('L_CONFIG_DESCRIPTION') ?></p>

<form action="parametres_plugin.php?p=maintenance" method="post">
	<fieldset class="withlabel">
		<h2><?php echo $plxPlugin->getLang('L_CONFIG_TITLE') ?></h2>
		<?php plxUtils::printInput('title', $plxPlugin->getParam('title'), 'text', '25-150'); ?>
		
		<h2><?php echo $plxPlugin->getLang('L_CONFIG_FAVICON') ?></h2>
		<?php plxUtils::printInput('favicon', $plxPlugin->getParam('favicon'), 'text', '25-250'); ?>
		
		<h2><?php echo $plxPlugin->getLang('L_CONFIG_CSS') ?></h2>
		<?php plxUtils::printArea('css', $plxPlugin->getParam('css'), 20, 75); ?>
		
		<h2><?php echo $plxPlugin->getLang('L_CONFIG_HTML') ?></h2>
		<?php plxUtils::printArea('html', $plxPlugin->getParam('html'), 20, 75); ?>

	</fieldset>
	<br />
	<?php echo plxToken::getTokenPostMethod() ?>
	<input type="submit" name="submit" value="<?php echo $plxPlugin->getLang('L_CONFIG_SAVE') ?>" />
</form>
