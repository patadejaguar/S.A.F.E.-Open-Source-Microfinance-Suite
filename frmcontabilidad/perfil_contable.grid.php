<?php
/**
 * @author Balam Gonzalez Luis Humberto
 * @version 1.0
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
$xHP		= new cHPage("TR.Perfil Contable de recibos", HP_GRID);
$xF			= new cFecha();
$xL			= new cLang();
$xTabla		= new cContable_polizas_perfil();
	
	$xHP->setNoDefaultCSS();
	echo $xHP->getHeader(true);
	//HTML Object END
	echo '<body onmouseup="SetMouseDown(false);" ><div id="onGrid">';
    //Define your grid
	$_SESSION["grid"]->SetDatabaseConnection(MY_DB_IN, USR_DB, PWD_DB);
	//Propiedades del GRID
	$mGridTitulo		= $xHP->getTitle();
	$mGridKeyField		= $xTabla->getKey();	//Nombre del Campo Unico
	$mGridKeyEdit		= true;					//Es editable el Campo
	$mGridTable			= $xTabla->get();	//Nombre de la tabla
	$mGridSQL			= $xTabla->query()->getListaDeCampos();//  "*"; //$xTabla->query()->getCampos();
	$mGridWhere			= "";

	$mGridProp			= array(
	"idcontable_poliza_perfil" 	=> "TR.ID,true,110",
	"tipo_de_recibo" 			=> "TR.CLAVE Recibo,true,110",
	"tipo_de_operacion" 		=> "TR.CLAVE operacion,true,110",
	"descripcion" 				=> "TR.DESCRIPCION,true,350",
	"operacion" 				=> "TR.OPERACION CONTABLE,true,110",
	"formula_posterior" 		=> "TR.FORMULA,true,300",
	"cuenta_alternativa" 		=> "TR.cuenta_CONTABLE alternativa,true,125"			
						);
	//===========================================================================================================
	
	$_SESSION["grid"]->SetSqlSelect($mGridSQL, $mGridTable, $mGridWhere);
	$_SESSION["grid"]->SetUniqueDatabaseColumn($mGridKeyField, $mGridKeyEdit);
	$_SESSION["grid"]->SetTitleName($mGridTitulo);
	$_SESSION["grid"]->SetEditModeAdd(false);
	//$_SESSION["grid"]->SetEditModeDelete(false);
	//===========================================================================================================					
		foreach ($mGridProp as $key => $value) {
			$mVals		= explode(",", $value, 3);
			if ( isset($mVals[0]) ){ $_SESSION["grid"]->SetDatabaseColumnName($key, $xL->getT($mVals[0]));	}
			if ( isset($mVals[1]) ) { $_SESSION["grid"]->SetDatabaseColumnEditable($key, $mVals[1]); }
			if ( isset($mVals[2]) ) { $_SESSION["grid"]->SetDatabaseColumnWidth($key, $mVals[2]); }		
		}
	//===========================================================================================================
	$_SESSION["grid"]->SetMaxRowsEachPage(40);
	$_SESSION["grid"]->PrintGrid(MODE_EDIT);

echo $xHP->end();
?>
