<?php
//conexion base de datos

$usuario = "root";
$contrasena = "s3tc0l0mb14";
$servidor = "74.208.177.175";
$basededatos = "db731511576";

$conexion=mysqli_connect($servidor,$usuario,$contrasena,$basededatos) or die ("No Conectado a la base de datos");

// Fila 1

//inclusiones columna pendientes del dia anterior e historial
$sql = "SELECT COUNT(ticket_id) FROM ost_ticket WHERE topic_id = 13 AND status_id = 1 AND created <= NOW()";
$rs  = mysqli_query($conexion,$sql);
$pendientes_dia_anterior = mysqli_fetch_array($rs);

//inclusiones columna recibidas
$fechahoy = date('Y-m-d');
$separar = explode("-", $fechahoy);
$dia = $separar[2];
$mes = $separar[1];
$anio = $separar[0];
$fechadia = $anio.'-'.$mes.'-'.$dia.' 00:00:01';
$sql = "SELECT COUNT(ticket_id) FROM ost_ticket WHERE topic_id = 13 AND status_id = 1 AND created >= '$fechadia' AND created <= NOW()";
$rs = mysqli_query($conexion,$sql);
$inc_recibidas = mysqli_fetch_array($rs);

//inclusiones columan gestionadas
$etiqueta = '{"status":[3,"Cerrado"]}';
//cerradas
$sql = "SELECT COUNT(id) FROM ost_thread_event WHERE topic_id = 13 AND state = 'closed' AND ost_thread_event.data LIKE '%$etiqueta%' AND ost_thread_event.timestamp >= '$fechadia' AND ost_thread_event.timestamp <= NOW()";
$rs = mysqli_query($conexion,$sql);
$rggc = mysqli_fetch_array($rs);
//devuelta uno
$etiqueta2 = '{"fields":{"75":["0","1"]}';
$sql = "SELECT COUNT(id) FROM ost_thread_event WHERE topic_id = 13 AND state = 'edited' AND ost_thread_event.data LIKE '%$etiqueta2%' AND ost_thread_event.timestamp >= '$fechadia' AND ost_thread_event.timestamp <= NOW()";
$rs = mysqli_query($conexion,$sql);
$rggd1 = mysqli_fetch_array($rs);
//devuelta dos
$etiqueta3 = '{"fields":{"77":["0","1"]}';
$sql = "SELECT COUNT(id) FROM ost_thread_event WHERE topic_id = 13 AND state = 'edited' AND ost_thread_event.data LIKE '%$etiqueta3%' AND ost_thread_event.timestamp >= '$fechadia' AND ost_thread_event.timestamp <= NOW()";
$rs = mysqli_query($conexion,$sql);
$rggd2 = mysqli_fetch_array($rs);
$inc_gestionadas = $rggc[0] + $rggd1[0] + $rggd2[0];

//inclusiones columan pendientes finalizando el dia
$pendientes_fin_dia = $pendientes_dia_anterior[0] + ($inc_recibidas[0] - $inc_gestionadas);

// Fila 2

//inclusiones devueltas columna pendientes_dia_anterior
//devuelta uno
$etiqueta4 = '{"fields":{"75":["0","1"]}';
$sql = "SELECT COUNT(id) FROM ost_thread_event WHERE topic_id = 13 AND state = 'edited' AND ost_thread_event.data LIKE '%$etiqueta4%' AND ost_thread_event.timestamp >= '$fechadia' AND ost_thread_event.timestamp <= NOW()";
$rs = mysqli_query($conexion,$sql);
$rggd3 = mysqli_fetch_array($rs);
//devuelta dos
$etiqueta5 = '{"fields":{"77":["0","1"]}';
$sql = "SELECT COUNT(id) FROM ost_thread_event WHERE topic_id = 13 AND state = 'edited' AND ost_thread_event.data LIKE '%$etiqueta5%' AND ost_thread_event.timestamp >= '$fechadia' AND ost_thread_event.timestamp <= NOW()";
$rs = mysqli_query($conexion,$sql);
$rggd4 = mysqli_fetch_array($rs);
$inc_dev_p_d_a = $rggd3[0] + $rggd4[0];

// Fila 3

//reproceso de inclusiones columnas pendientes_dia_anterior, recibidas y gestionadas, se toma calculos ya realizados en codigo arriba
$sql = "SELECT COUNT(ost_ticket.ticket_id) FROM ost_ticket inner join ost_ticket__cdata on ost_ticket.ticket_id = ost_ticket__cdata.ticket_id WHERE topic_id = 13 AND status_id = 7 AND reprocesouno = '1' AND lastupdate >= '$fechadia' AND lastupdate <= NOW()";
$rs = mysqli_query($conexion,$sql);
$reproceso_inc_p_d_a = mysqli_fetch_array($rs);

//reproceso uno
$etiqueta6 = '{"fields":{"76":["0","1"]}';
$sql = "SELECT COUNT(id) FROM ost_thread_event WHERE topic_id = 13 AND state = 'edited' AND ost_thread_event.data LIKE '%$etiqueta6%' AND ost_thread_event.timestamp >= '$fechadia' AND ost_thread_event.timestamp <= NOW()";
$rs = mysqli_query($conexion,$sql);
$reproceso_recib = mysqli_fetch_array($rs);

$reproceso_gestionadas = $rggc[0] + $rggd3[0] + $rggd4[0] + $reproceso_recib[0];

$reproceso_p_f_d = $reproceso_inc_p_d_a[0] + $reproceso_recib[0] - $reproceso_gestionadas;

// Fila 4

// reproceso de inclusiones devueltas columna pendientes del dia anterior
$sql = "SELECT COUNT(ost_ticket.ticket_id) FROM ost_ticket inner join ost_ticket__cdata on ost_ticket.ticket_id = ost_ticket__cdata.ticket_id WHERE topic_id = 13 AND status_id = 7 AND reprocesodos = '1' AND lastupdate >= '$fechadia' AND lastupdate <= NOW()";
$rs = mysqli_query($conexion,$sql);
$reproceso_inc_dev_p_d_a = mysqli_fetch_array($rs);

//reproceso de inclusiones devueltas columna recibidas
$etiqueta7 = '{"fields":{"78":["0","1"]}';
$sql = "SELECT COUNT(id) FROM ost_thread_event WHERE topic_id = 13 AND state = 'edited' AND ost_thread_event.data LIKE '%$etiqueta7%' AND ost_thread_event.timestamp >= '$fechadia' AND ost_thread_event.timestamp <= NOW()";
$rs = mysqli_query($conexion,$sql);
$reproceso_inc_dev_recib = mysqli_fetch_array($rs);

// Insercion de los resultados
$sql = "insert into reporte(inc_recibida_p_d_a,inc_recibida,inc_recibida_g,inc_recibida_p_f_d,inc_dev_p_d_a,rep_inc_p_d_a,rep_inc_recibida,rep_inc_g,rep_inc_p_f_d,rep_inc_dev_p_d_a,rep_inc_dev_recibida) values ('$pendientes_dia_anterior[0]','$inc_recibidas[0]','$inc_gestionadas','$pendientes_fin_dia','$inc_dev_p_d_a','$reproceso_inc_p_d_a[0]',$reproceso_recib[0],$reproceso_gestionadas,$reproceso_p_f_d,$reproceso_inc_dev_p_d_a[0],$reproceso_inc_dev_recib[0])";
$rs = mysqli_query($conexion,$sql);

?>