<?php
/**
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
$xHP		= new cHPage("", HP_FORM);
$xQL		= new MQL();
$xLi		= new cSQLListas();
$xF			= new cFecha();
$jxc 		= new TinyAjax();
function jsaGuardarCastigo($credito, $fecha, $razones){
	$xCred	= new cCredito($credito);
	$xCred->init();
	$xCred->setCastigado($razones, $fecha);
	return $xCred->getMessages();
}

$jxc ->exportFunction('jsaGuardarCastigo', array('idsolicitudactual', "idfecha", "idrazones"), "#idmsgs");
$jxc ->process();

$persona	= parametro("persona", DEFAULT_SOCIO, MQL_INT); $persona = parametro("socio", $persona, MQL_INT); $persona = parametro("idsocio", $persona, MQL_INT);
$credito	= parametro("credito", DEFAULT_CREDITO, MQL_INT); $credito = parametro("idsolicitud", $credito, MQL_INT); $credito = parametro("solicitud", $credito, MQL_INT);
$cuenta		= parametro("cuenta", DEFAULT_CUENTA_CORRIENTE, MQL_INT); $cuenta = parametro("idcuenta", $cuenta, MQL_INT);
$jscallback	= parametro("callback"); $tiny = parametro("tiny"); $form = parametro("form"); $action = parametro("action", SYS_NINGUNO);

$xHP->init();

$xFRM		= new cHForm("frmcastigos", "./");
$msg		= "";
$xFRM->addGuardar("jsaGuardarCastigo()");
$xFRM->ODate("idfecha", false, "TR.Fecha de Castigo");
$xFRM->OTextArea("idrazones", "", "TR.Razones del castigo");
$xFRM->addAviso("");
$xFRM->OHidden("idsolicitudactual", $credito, "");

echo $xFRM->get();
$jxc ->drawJavaScript(false, true);
$xHP->fin();
?>