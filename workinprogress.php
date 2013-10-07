<?php
ob_start();
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 3600');
header('X-Powered-By:');
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head><meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta name="language" content="fr" />
<meta http-equiv="content-language" content="fr" /><meta property="og:language" content="fr" />
<title>Mon site en dérangement</title>
<link rel="icon" type="image/x-icon" href="/plugins/favicon/img/favicon.ico" />
<link href="http://localhost:8888/PROJETS/AARCH/plugins/maintenance/style.css" rel="stylesheet" type="text/css">
</head><body><h1>
	Site en maintenance</h1>
<p>
	Nous sommes dans le cambouis ! Revenez plus tard ! ^_^</p>
<p>
	Merci de votre visite</p>
</body></html>