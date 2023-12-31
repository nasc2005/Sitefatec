<?php

namespace App\usuarios;
require "../vendor/autoload.php";

use App\Controller\UserController;

$users = new UserController();

$body = json_decode(file_get_contents('php://input'), true);
$id=isset($_GET['id'])?$_GET['id']:'';
switch($_SERVER["REQUEST_METHOD"]){
    case "POST":
        $emailUsuario = $body['email'];
        $usuarioExistente = $users->selectByEmail($emailUsuario);
        if (!empty($usuarioExistente)) {
            echo json_encode(['status' => false, 'message' => 'Usuário já existe']);
        } else {
            $resultado = $users->insert($body);
            echo json_encode(['status' => $resultado]);
        }
        break;
    
    case "GET":
        if (!isset($_GET['id'])) {
            $resultado = $users->select();
            echo json_encode(["usuarios" => $resultado]);
        } else {
            $resultado = $users->selectId($id);
            if (!empty($resultado)) {
                echo json_encode(["status" => true, "usuario" => $resultado[0]]);
            } else {
                echo json_encode(["status" => false, "usuario" => null]);
            }
        }
        break;
    
    case "PUT";
        $resultado = $users->update($body,intval($_GET['id']));
        echo json_encode(['status'=>$resultado]);
    break;
    case "DELETE";
        $resultado = $users->delete(intval($_GET['id']));
        echo json_encode(['status'=>$resultado]);
    break;  
}