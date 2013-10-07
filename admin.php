<?php 
if(!defined('PLX_ROOT')) exit; 

$plxMotor = plxMotor::getInstance();
$aMaintenance = array(
	0 => $plxPlugin->getLang('L_NO'),
	1 => $plxPlugin->getLang('L_YES')
);

# Control du token du formulaire
plxToken::validateFormToken($_POST);

if(!empty($_POST)) {
	$old = $plxPlugin->getParam('maintenance');
	if ($_POST['maintenance']==1 && ($old == 0 || $old == '' || $old == null)) {	
		$plxPlugin->setParam('ip', $_POST['ip'], 'cdata');
		$plxPlugin->setParam('maintenance', $_POST['maintenance'], 'numeric');
		$plxPlugin->saveParams();
		$plxPlugin->mkhtaccess();
	}elseif($_POST['maintenance']==0 && $old == 1) {
		$plxPlugin->setParam('ip', '', 'cdata');
		$plxPlugin->setParam('maintenance', $_POST['maintenance'], 'numeric');
		$plxPlugin->saveParams();
		$plxPlugin->delhtaccess();
	}
	header('Location: plugin.php?p=maintenance');
	exit;
}

?>

<h2><?php $plxPlugin->lang('L_TITLE'); ?></h2>
<p><?php $plxPlugin->lang('L_CONFIG_DESCRIPTION'); ?></p>
<p>&nbsp;</p>
<form action="plugin.php?p=maintenance" method="post">
	<p><?php echo $plxPlugin->getLang('L_CONFIG_IP') ?></p>
	<p><?php echo $plxPlugin->getLang('L_CONFIG_YOUR_IP').'&nbsp;"&nbsp;<strong>'.$_SERVER["REMOTE_ADDR"].'</strong>&nbsp;"';?></p>
		<?php plxUtils::printInput('ip', $plxPlugin->getParam('ip'), 'text', '25-150'); ?>
	<p>
	<p>&nbsp;</p>
	<label><?php echo $plxPlugin->getLang('L_PUT_IN_MAINTENANCE'); ?></label>
	<br/>
	<?php echo plxUtils::printSelect('maintenance', $aMaintenance, $plxPlugin->getParam('maintenance'));
	      echo plxToken::getTokenPostMethod() ?>
		<input class="button submit" type="submit" name="submit" value="<?php echo L_OK ?>" />
	</p>

</form>
