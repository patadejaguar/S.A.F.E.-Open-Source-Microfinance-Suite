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
$xDic		= new cHDicccionarioDeTablas();

ini_set("max_execution_time", 1600);

//$jxc 		= new TinyAjax();
//$tab = new TinyAjaxBehavior();
//$tab -> add(TabSetValue::getBehavior("idide", $x));
//return $tab -> getString();
//$jxc ->exportFunction('datos_del_pago', array('idsolicitud', 'idparcialidad'), "#iddatos_pago");
//$jxc ->process();


$clave			= parametro("id", 0, MQL_INT); $clave		= parametro("clave", $clave, MQL_INT);  
$fecha			= parametro("idfecha-0", false, MQL_DATE); $fecha = parametro("idfechaactual", $fecha, MQL_DATE);  $fecha = parametro("idfecha", $fecha, MQL_DATE);
$persona		= parametro("persona", DEFAULT_SOCIO, MQL_INT); $persona = parametro("socio", $persona, MQL_INT); $persona = parametro("idsocio", $persona, MQL_INT);
$credito		= parametro("credito", DEFAULT_CREDITO, MQL_INT); $credito = parametro("idsolicitud", $credito, MQL_INT); $credito = parametro("solicitud", $credito, MQL_INT);
$cuenta			= parametro("cuenta", DEFAULT_CUENTA_CORRIENTE, MQL_INT); $cuenta = parametro("idcuenta", $cuenta, MQL_INT);
$tipo			= parametro("tipo", 0, MQL_INT);
$monto			= parametro("monto",0, MQL_FLOAT); $monto	= parametro("idmonto",$monto, MQL_FLOAT); 
$recibo			= parametro("recibo", 0, MQL_INT); $recibo	= parametro("idrecibo", $recibo, MQL_INT);
$empresa		= parametro("empresa", 0, MQL_INT); $empresa	= parametro("idempresa", $empresa, MQL_INT); $empresa	= parametro("iddependencia", $empresa, MQL_INT); $empresa	= parametro("dependencia", $empresa, MQL_INT);
$grupo			= parametro("idgrupo", 0, MQL_INT); $grupo	= parametro("grupo", $grupo, MQL_INT);
$ctabancaria 	= parametro("idcodigodecuenta", 0, MQL_INT); $ctabancaria = parametro("cuentabancaria", $ctabancaria, MQL_INT);
$observaciones	= parametro("idobservaciones"); $observaciones	= parametro("observaciones", $observaciones);

$jscallback		= parametro("callback"); $tiny = parametro("tiny"); $form = parametro("form"); $action = parametro("action", SYS_NINGUNO);$action	= strtolower($action);

$xHP->init();

$xFRM		= new cHForm("frm", "./");
$xSel		= new cHSelect();
$xFRM->setTitle($xHP->getTitle());


$xCUtils		= new cUtileriasParaCreditos();
//1		USE LA UTILERIA 888
$FechaInicial	= $xF->getFechaMinimaOperativa();
$FechaFinal		= $xF->getFechaISO(fechasys());
$conSaldos		= true;
$xLog			= new cCoreLog();
$xLog->setSaveOnFile();

getEnCierre(true);

$xQL->setRawQuery("SET @fecha_de_corte='$FechaFinal';");

$xLog->add("============ GENERANDO MOVIMIENTOS DEL FIN DE MES V1.04\r\n");
$xLog->add( $xCUtils->setGenerarMvtoFinDeMes($FechaInicial, $FechaFinal, false, true) );

$xLog->add("============ GENERANDO SALDOS SPM DE CREDITOS HISTORICOS \r\n");
$xLog->add( $xCUtils->setReestructurarSDPM_Planes($conSaldos, false, true, $FechaFinal, $FechaInicial, true) );
$xLog->add( $xCUtils->setReestructurarSDPM($conSaldos, false, true, $FechaFinal, $FechaInicial, true) );


$xLog->add("============ GENERANDO INTERESES SOBRE SDPM HISTORICOS \r\n");
$xLog->add($xCUtils->setRegenerarInteresDevengado( false, $FechaInicial, $FechaFinal ));


$xLog->add("================== REESTRUCTURANDO SALDOS DE INTERESES EN CREDITOS\r\n ");
$xLog->add( $xCUtils->setAcumularIntereses(true, false,false));
//Actualizar Fecha
$fechaop				= fechasys();
$xQL->setRawQuery("SET @fecha_de_corte='$FechaFinal';");
$xQL->setRawQuery("CALL `sp_correcciones`()");
$xQL->setRawQuery("CALL `proc_creditos_letras_del_dia`()");
//rehacer intereses
$xLog->add($xCUtils->setAcumularMoraDeParcialidades());

getEnCierre(false);

$xFRM->addToolbar($xLog->getOFile()->getLinkDownload("TR.Descargar", ""));

/*
1		USE LA UTILERIA 888

2		USE LA UTILERIA 889

3		USE LA UTILERIA 900

4		USE LA UTILERIA 857*/

echo $xFRM->get();

//$jxc ->drawJavaScript(false, true);
$xHP->fin();
?>