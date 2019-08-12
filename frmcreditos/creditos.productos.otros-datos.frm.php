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
$xHP		= new cHPage("TR.OTROS PARAMETROS", HP_FORM);
$xQL		= new MQL();
$xLi		= new cSQLListas();
$xF			= new cFecha();
$xDic		= new cHDicccionarioDeTablas();
//$jxc = new TinyAjax();
//$jxc ->exportFunction('datos_del_pago', array('idsolicitud', 'idparcialidad'), "#iddatos_pago");
//$jxc ->process();

$producto	= parametro("producto", 0, MQL_INT);
$clave		= parametro("id", 0, MQL_INT); $clave		= parametro("clave", $clave, MQL_INT);  
$fecha		= parametro("idfecha-0", false, MQL_DATE); $fecha = parametro("idfechaactual", $fecha, MQL_DATE);  $fecha = parametro("idfecha", $fecha, MQL_DATE);
$persona	= parametro("persona", DEFAULT_SOCIO, MQL_INT); $persona = parametro("socio", $persona, MQL_INT); $persona = parametro("idsocio", $persona, MQL_INT);
$credito	= parametro("credito", DEFAULT_CREDITO, MQL_INT); $credito = parametro("idsolicitud", $credito, MQL_INT); $credito = parametro("solicitud", $credito, MQL_INT);
$cuenta		= parametro("cuenta", DEFAULT_CUENTA_CORRIENTE, MQL_INT); $cuenta = parametro("idcuenta", $cuenta, MQL_INT);
$jscallback	= parametro("callback"); $tiny = parametro("tiny"); $form = parametro("form"); $action = parametro("action", SYS_NINGUNO);
$monto		= parametro("monto",0, MQL_FLOAT); $monto	= parametro("idmonto",$monto, MQL_FLOAT); 
$recibo		= parametro("recibo", 0, MQL_INT); $recibo	= parametro("idrecibo", $recibo, MQL_INT);
$empresa	= parametro("empresa", 0, MQL_INT); $empresa	= parametro("idempresa", $empresa, MQL_INT); $empresa	= parametro("iddependencia", $empresa, MQL_INT);
$grupo		= parametro("idgrupo", 0, MQL_INT); $grupo	= parametro("grupo", $grupo, MQL_INT);
$ctabancaria = parametro("idcodigodecuenta", 0, MQL_INT); $ctabancaria = parametro("cuentabancaria", $ctabancaria, MQL_INT);

$observaciones= parametro("idobservaciones");
$xHP->init();

$xFRM		= new cHForm("frmcredsprododatos", "./");
$xSel		= new cHSelect();
$xTabla		= new cCreditos_productos_otros_parametros();
$xFRM->setTitle($xHP->getTitle());

$id			= $xTabla->query()->getLastID();

$xFRM->OHidden("idcreditos_productos_otros_parametros", $id, "TR.IDCREDITOS PRODUCTOS OTROS PARAMETROS");
$xFRM->OHidden("fecha_de_alta", $xF->getFechaISO());

$xFRM->ODate("fecha_de_expiracion", $xF->getFechaMaximaOperativa(), "TR.FECHA_DE VENCIMIENTO");
$xFRM->addHElem($xSel->getListaDeOtrosDatosEnProdDeCred("clave_del_parametro")->get(true));
$xFRM->OText("valor_del_parametro", "", "TR.VALOR DEL PARAMETRO");
$xFRM->OHidden("clave_del_producto", $producto);

$xFRM->addCRUD($xTabla->get(), true);
/*
$xFRM->OText("", $xTabla->valor_del_parametro()->v(), "TR.VALOR DEL PARAMETRO");
$xFRM->OText("", $xTabla->fecha_de_alta()->v(), "TR.FECHA DE ALTA");
$xFRM->OText("fecha_de_expiracion", $xTabla->fecha_de_expiracion()->v(), "TR.FECHA DE EXPIRACION");
 */
echo $xFRM->get();

//$jxc ->drawJavaScript(false, true);
$xHP->fin();
?>