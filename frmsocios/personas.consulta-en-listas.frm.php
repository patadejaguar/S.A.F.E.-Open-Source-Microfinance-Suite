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
$xHP		= new cHPage("TR.CONSULTA EN LISTA", HP_FORM);
$xQL		= new MQL();
$xLi		= new cSQLListas();
$xF			= new cFecha();
$xDic		= new cHDicccionarioDeTablas();
//$jxc 		= new TinyAjax();
//$jxc ->exportFunction('datos_del_pago', array('idsolicitud', 'idparcialidad'), "#iddatos_pago");
//$jxc ->process();
$clave		= parametro("id", 0, MQL_INT); $clave		= parametro("clave", $clave, MQL_INT);  
$fecha		= parametro("idfecha-0", false, MQL_DATE); $fecha = parametro("idfechaactual", $fecha, MQL_DATE);  $fecha = parametro("idfecha", $fecha, MQL_DATE);
$persona	= parametro("persona", DEFAULT_SOCIO, MQL_INT); $persona = parametro("socio", $persona, MQL_INT); $persona = parametro("idsocio", $persona, MQL_INT);
$credito	= parametro("credito", DEFAULT_CREDITO, MQL_INT); $credito = parametro("idsolicitud", $credito, MQL_INT); $credito = parametro("solicitud", $credito, MQL_INT);
$cuenta		= parametro("cuenta", DEFAULT_CUENTA_CORRIENTE, MQL_INT); $cuenta = parametro("idcuenta", $cuenta, MQL_INT);
$jscallback	= parametro("callback"); $tiny = parametro("tiny"); $form = parametro("form"); $action = parametro("action", SYS_NINGUNO);
$monto		= parametro("monto",0, MQL_FLOAT); $monto	= parametro("idmonto",$monto, MQL_FLOAT); 
$recibo		= parametro("recibo", 0, MQL_INT); $recibo	= parametro("idrecibo", $recibo, MQL_INT);
$empresa	= parametro("empresa", 0, MQL_INT); $empresa	= parametro("idempresa", $empresa, MQL_INT); $empresa	= parametro("iddependencia", $empresa, MQL_INT); $empresa	= parametro("dependencia", $empresa, MQL_INT);
$grupo		= parametro("idgrupo", 0, MQL_INT); $grupo	= parametro("grupo", $grupo, MQL_INT);
$ctabancaria = parametro("idcodigodecuenta", 0, MQL_INT); $ctabancaria = parametro("cuentabancaria", $ctabancaria, MQL_INT);

$observaciones= parametro("idobservaciones");
$xHP->addJTableSupport();
$xHP->init();



$xFRM	= new cHForm("frmconsultalistas", "personas.consulta-en-listas.frm.php?action=$action");
$xSel		= new cHSelect();
$xFRM->setTitle($xHP->getTitle());
$xFRM->addCerrar();


/* ===========		GRID JS		============*/
$xFRM->OHidden("idpersona", $persona);
$xHG	= new cHGrid("iddivconsultalista",$xHP->getTitle());

$xHG->setSQL($xLi->getListadoDePersonasConsultasL($persona));
$xHG->addList();
$xHG->addKey("clave");

//$xHG->col("persona", "TR.PERSONA", "10%");
$xHG->col("fecha", "TR.FECHA", "10%");
$xHG->col("tipo", "TR.TIPO", "10%");

//$xHG->col("tiempo", "TR.TIEMPO", "10%");
//$xHG->col("url", "TR.URL", "10%");

$xHG->col("proveedor", "TR.PROVEEDOR", "10%");

$xHG->col("nombre", "TR.Nombre", "10%");

$xHG->col("usuario", "TR.USUARIO", "10%");
//$xHG->col("coincidente", "TR.COINCIDENTE", "10%");
//$xHG->col("razones", "TR.RAZONES", "10%");

$xHG->OToolbar("TR.AGREGAR", "jsAdd()", "grid/add.png");

//$xHG->OButton("TR.EDITAR", "jsEdit('+ data.record.clave +')", "edit.png");
$xHG->OButton("TR.ELIMINAR", "jsDel('+ data.record.clave +')", "delete.png");

$xHG->OButton("TR.CONSULTA", "jsConsultaPorId('+ data.record.clave +')", "view.png");
$xFRM->addHElem("<div id='iddivconsultalista'></div>");
$xFRM->addJsCode( $xHG->getJs(true) );

//Logs

$sql	= "SELECT  `general_log`.`idgeneral_log` AS `clave`, `general_log`.`fecha_log` AS `fecha`,
         `general_log`.`text_log` AS `resultado`
FROM     `general_log`
WHERE    ( `general_log`.`type_error` = 103 ) AND ( `general_log`.`idpersona` = $persona )";
$xT		= new cTabla($sql);

$xFRM->addHElem( $xT->Show("Sin Resultados") );

echo $xFRM->get();
?>
<script>
var xG	= new Gen();
function jsGetConsultaSAFE(str){
	str	= base64.decode(str);
	xG.w({url:str+"&report=true"});
}
function jsConsultaPorId(id){
	xG.w({url: "../frmpld/visor-consulta.frm.php?clave=" + id});
}
function jsEdit(id){
	//xG.w({url:"../frmsocios/personas.consulta-lista-negra.edit.frm.php?clave=" + id, tiny:true, callback: jsLGiddivconsultalista});
}
function jsAdd(){
	idpersona = $("#idpersona").val();
	xG.w({url:"../frmsocios/personas.consulta-en-listas.new.frm.php?persona=" + idpersona, tiny:true, callback: jsLGiddivconsultalista});
}
function jsDel(id){
	xG.rmRecord({tabla:"personas_consulta_lista", id:id, callback:jsLGiddivconsultalista});
}
function jsDescartarPorId(id){
	xG.save({tabla:"personas_consulta_lista", id: id });
}
/*function jsDescartarSalvarPorId(){
	
}*/
</script>
<?php
//$jxc ->drawJavaScript(false, true);
$xHP->fin();
?>