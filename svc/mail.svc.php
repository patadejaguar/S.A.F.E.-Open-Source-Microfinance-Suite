<?php
/**
 * Modulo
 * @author Balam Gonzalez Luis Humberto
 * @version 0.0.01
 * @package
 */
//=====================================================================================================
	include_once("../core/go.login.inc.php");
	include_once("../core/core.error.inc.php");
	include_once("../core/core.html.inc.php");
	include_once("../core/core.init.inc.php");
	include_once("../core/core.db.inc.php");
	$theFile			= __FILE__;
	$permiso			= getSIPAKALPermissions($theFile);
	if($permiso === false){	header ("location:../404.php?i=999");	}
	$_SESSION["current_file"]	= addslashes( $theFile );
//=====================================================================================================
$xHP		= new cHPage("", HP_SERVICE);
//$xQL		= new MQL();
//$xLi		= new cSQLListas();
$xF			= new cFecha();


$clave			= parametro("id", 0, MQL_INT); $clave		= parametro("clave", $clave, MQL_INT);  
$fecha			= parametro("idfecha-0", false, MQL_DATE); $fecha = parametro("idfechaactual", $fecha, MQL_DATE); 
$persona		= parametro("persona", DEFAULT_SOCIO, MQL_INT); $persona = parametro("socio", $persona, MQL_INT); $persona = parametro("idsocio", $persona, MQL_INT);
$credito		= parametro("credito", DEFAULT_CREDITO, MQL_INT); $credito = parametro("idsolicitud", $credito, MQL_INT); $credito = parametro("solicitud", $credito, MQL_INT);
$cuenta			= parametro("cuenta", DEFAULT_CUENTA_CORRIENTE, MQL_INT); $cuenta = parametro("idcuenta", $cuenta, MQL_INT);
$jscallback		= parametro("callback"); $tiny = parametro("tiny"); $form = parametro("form"); $action = parametro("action", SYS_NINGUNO);$action = parametro("cmd", $action);
$monto			= parametro("monto",0, MQL_FLOAT); $monto	= parametro("idmonto",$monto, MQL_FLOAT); 
$recibo			= parametro("recibo", 0, MQL_INT); $recibo	= parametro("idrecibo", $recibo, MQL_INT);
$observaciones	= parametro("idobservaciones");
$letra			= parametro("letra", false, MQL_INT);

$idd1			= parametro("idd1", "", MQL_RAW);
$idd2			= parametro("idd2", "", MQL_RAW);
$title			= parametro("title", "", MQL_RAW);
$senders		= getEmails($_REQUEST);

$xSVC			= new MQLService($action, "");

$rs				= array();
$rs["error"]	= true;
$rs["message"]	= "Sin datos validos";

$idd1			= str_replace(" ", "+", $idd1);
$idd2			= str_replace(" ", "+", $idd2);



$bb				= ($idd1 == "") ? "" : base64_decode($idd1);
$bb				.= ($idd2 == "") ? "" : base64_decode($idd2);




$xNot			= new cNotificaciones();
foreach ($senders as $idx => $idm){
	$xNot->sendMail($title, $bb, $idm );
	$rs["error"]	= false;
}

$rs["message"]	= $xNot->getMessages();




header('Content-type: application/json');
echo json_encode($rs);
?>