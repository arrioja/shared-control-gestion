<?php 
/*
* Este archivo realiza el llamado al modulo para la inserci�n de los planes estrat�gicos
*@ Versi�n 1.0 @Modificado: 28 de Marzo del 2008
*@Autores: Pa�l Gonz�lez y Rosanny Y��ez
*
*/?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include "../templates/CENE_MENU_DIRECCION.dwt" ?>

<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function confirma_eliminar(cod,plan_estrategico_direccion)
{
if (confirm("Se dispone a eliminar el plan estrat�gico: "+plan_estrategico_direccion+". �Desea continuar?"))
   elimina_plan_estrategico_direccion(cod);
}

function nuevoAjax()
{
   /* Crea el objeto AJAX*/
   var xmlhttp=false;
   try
   {
      // Creacion del objeto AJAX para navegadores no IE
      xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
   }
   catch(e)
   {
      try
      {
         // Creacion del objet AJAX para IE
         xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch(E) { xmlhttp=false; }
   }
   if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); }

   return xmlhttp;
}



function carga_direccion()
{
   var valor=document.getElementById("select_0").options[document.getElementById("select_0").selectedIndex].value;
   if(valor==0)
   {
      // Si el usuario eligio la opcion "Elige", no voy al servidor y pongo todo por defecto
      combo=document.getElementById("select_1");
      combo.length=0;
      var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Selecciona Direcci�n...";
      combo.appendChild(nuevaOpcion);   combo.disabled=true;
	 
   }
   else
   {
      ajax=nuevoAjax();
      ajax.open("GET", "../select/select_total.php?seleccionado="+valor+"&select=4", true);
      ajax.onreadystatechange=function()
      {
         if (ajax.readyState==1)
         {
            // Mientras carga elimino la opcion "Elige " y se coloca una que dice "Cargando"
            combo=document.getElementById("select_1");
            combo.length=0;
            var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Cargando...";
            combo.appendChild(nuevaOpcion); combo.disabled=true;   
         }
         if (ajax.readyState==4)
         {
            document.getElementById("cod").innerHTML=ajax.responseText;
         }
      }
      ajax.send(null);
   }
}

function carga_plan()
{
   var valor=document.getElementById("select_1").options[document.getElementById("select_1").selectedIndex].value;
   var codigo=document.getElementById("select_0").options[document.getElementById("select_0").selectedIndex].value;

   if(valor==0){
    document.getElementById("plan").innerHTML="No disponible";
   }
   else
   {
      ajax=nuevoAjax();
	 // document.write(valor);
      ajax.open("GET", "muestra_planes_estrategicos_direcciones.php?seleccionado="+valor+"&cod_org="+document.form1.organizacion.value, true);
      ajax.onreadystatechange=function()
      {
         if (ajax.readyState==1)
         {
            document.getElementById("plan").innerHTML="Cargando....";
         }
         if (ajax.readyState==4)
         {
            document.getElementById("plan").innerHTML=ajax.responseText;
			
         }
      }
      ajax.send(null);
   }
}

function elimina_plan_estrategico_direccion(cod)
{
      ajax=nuevoAjax();
      ajax.open("GET", "../eliminar/eliminar.php?seleccionado="+cod+"&elimina=5", true);
      ajax.onreadystatechange=function()
      {
         if (ajax.readyState==4)
		 {
            document.getElementById("plan2").innerHTML=ajax.responseText;
		    carga_plan();
		}
      }
      ajax.send(null);
}

</script>


<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema Gesti�n/Direcci�n/Listado de Planes Estrat�gicos</title>

<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="../css/tablas.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php

function genera_organizacion()
{
   include ("../conexion/conectar.php");
   $result=mysql_query("SELECT * FROM gestion_organizacion");
   mysql_close($link);
   // Muestra el primer select compuesto por las organizaciones
   echo "<select class='combo' id='select_0' name='organizacion' onChange='carga_direccion()'>",
      "<option value='0'>Elige Organizaci�n....</option>";
	  
   while($row=mysql_fetch_row($result))
   {
      echo "<option value='".$row[1]."'>".$row[2]."</option>";
   }
   echo "</select>";
}
?>


<form id="f1" name="form1" method="POST" action="planes_estrategicos_direccion.php">
  <table width="600" border="1" align="center" cellpadding="2">
    <tr class="encabezado">
      <td colspan="2" id="fila_1"><div align="center"><strong><img src="../imag/j0432636.png" alt="" width="54" height="40" />Listado de Planes Estrat&eacute;gicos</strong> <strong>Direcci&oacute;n</strong>
          <input type="hidden" name="insertar" id="insertar" value="insertar" />
      </div></td>
    </tr>
    <tr>
      <td><strong>Organizaci&oacute;n</strong></td>
      <td><?php genera_organizacion(); ?> &nbsp;</td>
    </tr>
    <tr>
      <td width="25%" align="" ><strong>Direcci&oacute;n</strong></td>
      <td width="75%" align="" id="cod"> <div align="justify">
        <select class="combo" disabled="disabled" id="select_1" name="direccion" onChange="carga_plan();">
          <option value="0">Selecciona Direcci&oacute;n...</option>
        </select>
     </div>  </td>
    </tr>
    <tr>
      <td colspan="2" id="plan">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" id="plan2">&nbsp;</td>
    </tr>
  </table>
  <label></label>
  <p>&nbsp;</p>
</form>

</body>
</html>
