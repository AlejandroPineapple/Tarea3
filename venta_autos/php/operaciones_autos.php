<?php

include('conn.php');

if(isset($_GET['accion']))
{
    $accion = $_GET['accion'];

    if($accion == 'leer')
    {
        $sql = "SELECT * FROM autos WHERE 1";
        $result = $db->query($sql);

        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $item['id'] =  $row['id'];
                $item['marca'] = $row['marca'];
                $item['modelo'] = $row['modelo'];
                $item['año'] = $row['año'];
                $item['serie'] = $row['serie'];
                $arrAlumnos[] = $item;
            }
            $response ["status"] = "Ok";
            $response ["mensaje"] = $arrAlumnos;
        }
        else
        {
            $response ["status"] = "Error";
            $response ["mensaje"] = "Bruh, No hay autos registrados";
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

//Si yo paso los datos como JSON a traves del body
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data)) {
    
    //obtengo la accion
    $accion = $data["accion"];
    //verifico el tipo de accion

    if($accion=='insertar') {

        //obtener los demas datos del body
        $marca = $data["marca"];
        $modelo = $data["modelo"];
        $año = $data["año"];
        $serie = $data["serie"];

        $qry = "insert into autos (marca, modelo, año, serie) values ('$marca','$modelo','$año','$serie')";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'Eso mamon, el registro se creo correctamente';

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
        $marca = $data["marca"];
        $modelo = $data["modelo"];
        $año = $data["año"];
        $serie = $data["serie"];

        $qry = "update autos set marca = '$marca', modelo = '$modelo', año = '$año', serie = '$serie' WHERE id = '$id'";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'Wuuuuu. El registro se modifico correctamente';

        } else {
            $response["status"] = 'ERROR';
            $response["mensaje"] = 'No se pudo guardar, valio queso';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    if ($accion== "borrar")
    {
        $id = $data["id"];

        $qry = "delete from autos WHERE id = '$id'";

        if($db->query($qry)) {

            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se mato correctamente';

        } else {
            $response["status"] = 'ERROR';
            $response["mensaje"] = 'No se pudo matar, valio queso';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}