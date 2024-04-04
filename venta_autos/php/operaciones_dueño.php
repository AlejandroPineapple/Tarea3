<?php

include('conn.php');

if(isset($_GET['accion']))
{
    $accion = $_GET['accion'];

    if($accion == 'leer')
    {
        $sql = "SELECT autos.id AS auto_id, autos.marca, autos.modelo, autos.año, autos.serie, 
                       IFNULL(clientes.nombre, 'Sin dueño') AS nombre_cliente, 
                       IFNULL(clientes.email, '') AS email_cliente
                FROM autos
                LEFT JOIN dueño ON autos.id = dueño.auto_id
                LEFT JOIN clientes ON dueño.cliente_id = clientes.id";

        $result = $db->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $item['nombre_cliente'] = $row['nombre_cliente'];
                $item['marca'] = $row['marca'];
                $item['modelo'] = $row['modelo'];
                $item['año'] = $row['año'];
                $item['serie'] = $row['serie'];
                $arrDueñosAutos[] = $item;
            }
            $response ["status"] = "Ok";
            $response ["mensaje"] = $arrDueñosAutos;
        }
        else
        {
            $response ["status"] = "Error";
            $response ["mensaje"] = "Bruh, No hay registros de dueño de autos";
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

//Si se pasan los datos como JSON a través del body
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data)) {
    
    //obtener la acción
    $accion = $data["accion"];
    //verificar el tipo de acción

    if($accion=='insertar') {

        //obtener los demás datos del body
        $cliente_id = $data["cliente_id"];
        $auto_id = $data["auto_id"];

        $qry = "INSERT INTO dueño (cliente_id, auto_id) VALUES ('$cliente_id','$auto_id')";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'Eso mamon, el registro se creó correctamente';

        } else {
            $response["status"] = 'ERROR';
            $response["mensaje"] = 'Chispas, no se pudo guardar el registro debido a un error';
        }
        header('Content-Type: application/json');

        echo json_encode($response);
    }

    if ($accion== "modificar")
    {
        $id = $data["id"];
        $cliente_id = $data["cliente_id"];
        $auto_id = $data["auto_id"];

        $qry = "UPDATE dueño SET cliente_id = '$cliente_id', auto_id = '$auto_id' WHERE id = '$id'";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'Wuuuuu. El registro se modificó correctamente';

        } else {
            $response["status"] = 'ERROR';
            $response["mensaje"] = 'No se pudo guardar el registro, valio queso';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    if ($accion== "borrar")
    {
        $id = $data["id"];

        $qry = "DELETE FROM dueño WHERE id = '$id'";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se eliminó correctamente';

        } else {
            $response["status"] = 'ERROR';
            $response["mensaje"] = 'No se pudo eliminar el registro, valio queso';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

}

?>