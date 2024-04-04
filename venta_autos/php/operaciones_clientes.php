<?php

include('conn.php');

if(isset($_GET['accion']))
{
    $accion = $_GET['accion'];

    if($accion == 'leer')
    {
        $sql = "SELECT * FROM clientes WHERE 1";
        $result = $db->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $item['id'] =  $row['id'];
                $item['nombre'] = $row['nombre'];
                $item['apellido'] = $row['apellido'];
                $item['email'] = $row['email'];
                $arrClientes[] = $item;
            }
            $response ["status"] = "Ok";
            $response ["mensaje"] = $arrClientes;
        }
        else
        {
            $response ["status"] = "Error";
            $response ["mensaje"] = "Bruh, No hay clientes registrados";
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

//Si se pasa los datos como JSON a través del body
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data)) {
    
    //obtener la acción
    $accion = $data["accion"];
    //verificar el tipo de acción

    if($accion=='insertar') {

        //obtener los demás datos del body
        $nombre = $data["nombre"];
        $apellido = $data["apellido"];
        $email = $data["email"];

        $qry = "INSERT INTO clientes (nombre, email, apellido) VALUES ('$nombre','$email', '$apellido')";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'Eso mamon, el cliente se creó correctamente';

        } else {
            $response["status"] = 'ERROR';
            $response["mensaje"] = 'Chispas, no se pudo guardar el cliente debido a un error';
        }
        header('Content-Type: application/json');

        echo json_encode($response);
    }

    if ($accion== "modificar")
    {
        $id = $data["id"];
        $nombre = $data["nombre"];
        $apellido = $data["apellido"];
        $email = $data["email"];

        $qry = "UPDATE clientes SET nombre = '$nombre', email = '$email', apellido = '$apellido' WHERE id = '$id'";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'Wuuuuu. El cliente se modificó correctamente';

        } else {
            $response["status"] = 'ERROR';
            $response["mensaje"] = 'No se pudo guardar el cliente, valio queso';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    if ($accion== "borrar")
    {
        $id = $data["id"];

        $qry = "DELETE FROM clientes WHERE id = '$id'";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'El cliente se eliminó correctamente';

        } else {
            $response["status"] = 'ERROR';
            $response["mensaje"] = 'No se pudo eliminar el cliente, valio queso';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

?>