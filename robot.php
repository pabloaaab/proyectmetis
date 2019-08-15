<?php
//conexion base de datos x2crm

$usuario = "root";
$contrasena = "";
$servidor = "localhost";
$basededatos = "bdcartas";

$usuario2 = "root";
$contrasena2 = "";
$servidor2 = "localhost";
$basededatos2 = "bdproyectmetis";

//$enlace = mysqli_connect($servidor,$usuario,$contrasena,$basededatos) or die ("No Conectado a la base de datos de cartas");

$enlace = mysqli_connect($servidor, $usuario, $contrasena, $basededatos) or die ("No Conectado a la base de datos 1");

$enlace2 = mysqli_connect($servidor2, $usuario2, $contrasena2, $basededatos2) or die ("No Conectado a la base de datos 2");

/* comprobar la conexión */
if (mysqli_connect_errno()) {
    printf("Falló la conexión: %s\n", mysqli_connect_error());
    exit();
}

//
$fecha = date('Y-m-d');
$fecha_int = strtotime($fecha);

$consulta = "SELECT 
x2_list_items.id AS id_carta,
x2_list_items.emailAddress AS email_enviado,
x2_list_items.contactId AS cliente_enviado,
x2_contacts.name AS nombre_completo,
x2_contacts.email AS email,
x2_contacts.firstName AS nombres,
x2_contacts.lastName AS apellidos,
x2_contacts.phone AS telefono_1,
x2_contacts.phone2 AS telefono_2,
x2_contacts.company AS compania,
x2_contacts.website AS pagina,
x2_contacts.address AS direccion_1,
x2_contacts.address2 AS direccion_2,
x2_contacts.c_ciudad AS ciudad,
x2_contacts.state AS departamento,
x2_contacts.country AS pais,
x2_contacts.c_placa AS placa,
x2_list_items.listId AS id_campania,
x2_lists.nameId AS campania_id,
x2_lists.name AS campania,
FROM_UNIXTIME(sent,'%Y-%m-%d %H:%i:%s') AS fecha_envio,
x2_campaigns.subject AS tema,
x2_action_text.text AS mensaje
FROM x2_list_items
INNER JOIN x2_lists ON x2_list_items.listId = x2_lists.id
INNER JOIN x2_contacts ON x2_list_items.contactId = x2_contacts.id
INNER JOIN x2_campaigns ON x2_list_items.listId = x2_campaigns.id
INNER JOIN x2_actions ON x2_list_items.contactId = x2_actions.associationId
INNER JOIN x2_action_text ON x2_actions.id = x2_action_text.id
WHERE FROM_UNIXTIME(x2_list_items.sent,'%Y-%m-%d %H:%i:%s') LIKE '%date('Y-m-d')%'
AND x2_list_items.sent <> 0
AND x2_list_items.sending = 0
AND x2_actions.complete = 'yes'
GROUP BY x2_list_items.contactId";

$resultado = mysqli_query($enlace,$consulta) or die ("error consulta select $consulta");

/* obtener el array asociativo */
while ($obj = mysqli_fetch_object($resultado)) {
    //se busca el cliente si esta registrado en la plataforma de reportes
    $cliente = "select id_cliente from cliente where placa = '$obj->placa' ";
    $result = mysqli_query($enlace2, $cliente) or die ("error consulta verificar si existe cliente $cliente");
    $reg = mysqli_num_rows($result);
    if ($reg == 0){ //insercion
        //se inserta el cliente en el caso que no este registrado en la plataforma de reportes
        $insertcliente = "insert into cliente(nombre_id,nombres,apellidos,nombre_completo,compania,telefono_1,telefono_2,email,pagina,direccion_1,direccion_2,ciudad,departamento,pais,placa)"
                . "       values ('$obj->nombre_completo','$obj->nombres','$obj->apellidos','$obj->nombre_completo','$obj->campania','$obj->telefono_1','$obj->telefono_2','$obj->email','$obj->pagina','$obj->direccion_1','$obj->direccion_2','$obj->ciudad','$obj->departamento','$obj->pais','$obj->placa')";
        $resuult = mysqli_query($enlace2, $insertcliente) or die ("error consulta insercion cliente $insertcliente");
        //se consulta el consecutivo creado del nuevo cliente
        $clientenuevo = "select id_cliente from cliente where placa = '$obj->placa' ";
        $result = mysqli_query($enlace2, $clientenuevo) or die ("error consulta verificar le consecutivo de la placa $clientenuevo");
        $datos = mysqli_fetch_object($result);
        //se inserta los registros de las notificaciones enviadas en la plataforma de reportes
        $consultainsert = "insert into reporte (id_cliente,id_proceso,id_campania,campania,tema,mensaje,email_enviado,fecha_enviado,placa)"
                . "        values ('$datos->id_cliente','1','$obj->id_campania','$obj->campania','$obj->tema','$obj->mensaje','$obj->email_enviado','$obj->fecha_envio','$obj->placa')";
        $resuult = mysqli_query($enlace2, $consultainsert) or die ("error consulta insercion  reporte $consultainsert");
    }else{ // modificacion
        $editcliente = "update cliente set nombre_id = '$obj->nombre_completo', nombres = '$obj->nombres', apellidos = '$obj->apellidos', nombre_completo = '$obj->nombre_completo', campania = '$obj->'campania', telefono_1 = '$obj->telefono_1', telefono_2 = '$obj->telefono_2', email = '$obj->email', pagina = '$obj->pagina', direccion_1 = '$obj->direccion_1', direccion_2 = '$obj->direccion_2', ciudad = '$obj->ciudad', departamento = '$obj->departamento', pais = '$obj->pais '"
                . " where placa = $clientenuevo->placa";
        $resuult = mysqli_query($enlace2, $editcliente) or die ("error consulta edicion cliente $edittcliente");
        //se inserta los registros de las notificaciones enviadas en la plataforma de reportes edicion
        $consultainsert = "insert into reporte (id_cliente,id_proceso,id_campania,campania,tema,mensaje,email_enviado,fecha_enviado,placa)"
                . "        values ('$cliente->id_cliente','1','$obj->id_campania','$obj->campania','$obj->tema','$obj->mensaje','$obj->email_enviado','$obj->fecha_envio','$obj->placa')";
        $resuult = mysqli_query($enlace2, $consultainsert) or die ("error consulta insercion  reporte $consultainsert");
    }

}

/* cerrar la conexión */
mysqli_close($enlace2);

/* liberar el conjunto de resultados */
mysqli_free_result($resultado);

/* cerrar la conexión */
mysqli_close($enlace);




?>