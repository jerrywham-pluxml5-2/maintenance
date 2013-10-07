<?php
/**
 * Plugin Maintenance
 *
 * @package	PLX
 * @version	1.1
 * @date	07/10/2013
 * @author	Cyril MAGUIRE
 **/
class maintenance extends plxPlugin {

	/**
	 * Constructeur de la classe Auth
	 *
	 * @param	default_lang	langue par défaut utilisée par PluXml
	 * @return	null
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);
		
		$this->setAdminProfil(PROFIL_ADMIN);
		$this->setConfigProfil(PROFIL_ADMIN);

		$this->addHook('AdminTopBottom', 'AdminTopBottom');	
	}

	/**
	 * Méthode qui préconfigure le plugin
	 *
	 * @return	stdio
	 * @author	Cyril MAGUIRE
	 **/
	public function onActivate() {
		$plxAdmin = plxAdmin::getInstance();
		#Paramètres par défaut
		if(!is_file($this->plug['parameters.xml'])) {
			$this->setParam('title', plxUtils::strCheck($plxAdmin->aConf['title']).' | Maintenance', 'cdata');
			$this->setParam('favicon', '', 'cdata');
			$this->setParam('css', '', 'cdata');
			$this->setParam('html', '<h1>Site en maintenance</h1><p>Nous sommes dans le cambouis ! Revenez plus tard ! ^_^</p><p>	Merci de votre visite</p>', 'cdata');
			$this->saveParams();
		}
	}


	/**
	 * Méthode qui affiche un message s'il y a un message à afficher
	 *
	 * @return	stdio
	 * @author	Stephane F, Cyril MAGUIRE
	 **/
	public function AdminTopBottom() {
		
			$string = '
			if (str_pad(str_replace(".","",$plxAdmin->aConf["version"]), 3, "0", STR_PAD_RIGHT) < 520 ) {
				$maintenance = $plxAdmin->plxPlugins->aPlugins["maintenance"]["instance"];
			} else {
				$maintenance = $plxAdmin->plxPlugins->aPlugins["maintenance"];
			}
			if($maintenance->getParam("maintenance")==1 && $maintenance->getParam("ip")!="") {
				echo "<p class=\"notice\">".$maintenance->getLang("L_MAINTENANCE_ACTIVATED")."</p>";
				plxMsg::Display();
			}';
			echo '<?php '.$string.' ?>';
	}

	public function mkcss() {
		file_put_contents(PLX_PLUGINS.'maintenance/style.css', $this->getParam('css'));
	}
	public function mkhtml() {
		$plxMotor = plxMotor::getInstance();
		$data = '<?php'."\n";
		$data .= 'ob_start();'."\n";
		$data .= 'header(\'HTTP/1.1 503 Service Temporarily Unavailable\');'."\n";
		$data .= 'header(\'Status: 503 Service Temporarily Unavailable\');'."\n";
		$data .= 'header(\'Retry-After: 3600\');'."\n";
		$data .= 'header(\'X-Powered-By:\');'."\n";
		$data .= '?><!DOCTYPE html>'."\n";
		$data .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$plxMotor->aConf['default_lang'].'" lang="'.$plxMotor->aConf['default_lang'].'">'."\n";
		$data .= '<head><meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />'."\n";
		$data .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta name="language" content="'.$plxMotor->aConf['default_lang'].'" />'."\n";
		$data .= '<meta http-equiv="content-language" content="'.$plxMotor->aConf['default_lang'].'" /><meta property="og:language" content="'.$plxMotor->aConf['default_lang'].'" />'."\n";
		$data .= '<title>'.$this->getParam('title').'</title>'."\n";
		$data .= '<link rel="icon" type="image/x-icon" href="'.$this->getParam('favicon').'" />'."\n";
		$data .= '<link href="'.$plxMotor->racine.'plugins/maintenance/style.css" rel="stylesheet" type="text/css">'."\n";
		$data .= '</head><body>'.$this->getParam('html').'</body></html>';
		file_put_contents(PLX_PLUGINS.'maintenance/workinprogress.php', $data);
	}
	public function mkhtaccess() {
		$plxMotor = plxMotor::getInstance();
		$ht = '<IfModule mod_rewrite.c>'."\n";
		$ht .= 'RewriteEngine on'."\n";
		$ht .= 'RewriteCond %{REQUEST_URI} !/plugins/maintenance/workinprogress.php$'."\n";
		$ht .= 'RewriteCond %{REMOTE_ADDR} !'.$this->getParam('ip')."\n";
		$ht .= 'RewriteRule ^(.*)$ '.$plxMotor->racine.'plugins/maintenance/workinprogress.php [L]'."\n";
		$ht .= '</IfModule>';
		$content = '';
		if(is_file(PLX_ROOT.'.htaccess'))
			$content = implode('', file(PLX_ROOT.'.htaccess'));

        if(preg_match("/^(.*)# BEGIN -- Pluxml.*# END -- Pluxml(.*)$/ms", $content, $capture) !== false) {
            rename(PLX_ROOT.'.htaccess', PLX_ROOT.'htaccess.txt');
            plxUtils::write($ht, PLX_ROOT.'.htaccess');
        }
		
	}
	public function delhtaccess() {
		if (is_file(PLX_ROOT.'.htaccess')) {
			$content = '';
			if (is_file(PLX_ROOT.'htaccess.txt')) {
				$content = implode('', file(PLX_ROOT.'.htaccess'));
			}

	        if(preg_match("/^(.*)# BEGIN -- Pluxml.*# END -- Pluxml(.*)$/ms", $content, $capture) !== false) {
					$ht = file_get_contents(PLX_ROOT.'htaccess.txt');
					plxUtils::write($ht, PLX_ROOT.'.htaccess');
					unlink(PLX_ROOT.'htaccess.txt');
	        }
		}
	}

}
?>