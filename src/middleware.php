<?php

require_once "../app/clases/token.php";

class Middleware {

    public static function ValidarToken($request, $response, $next){
        $token = $request->getHeader("token");
        $validacionToken = Token::VerificarToken($token[0]);

        if( $validacionToken["Estado"] == "OK" ){
            $request = $request->withAttribute("payload", $validacionToken);
            return $next($request, $response);
        }
        else {
            $newResponse = $response->withJson($validacionToken, 401);
            return $newResponse;
        }
    }

    public static function ValidarSocio($request, $response, $next) {
        $payload = $request->getAttribute("payload")["Payload"];
        $data = $payload->data;

        if($data->tipo_empleado == "socio")
            return $next($request, $response);
        
        else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "No tiene permisos para realizar esta acción.");
            $newResponse = $response->withJson($respuesta, 401);
            return $newResponse;
        }
    }

    public static function ValidarMozo($request, $response, $next) {
        $payload = $request->getAttribute("payload")["Payload"];
        $data = $payload->data;
        
        if($data->tipo_empleado == "mozo" || $data->tipo_empleado == "socio")
            return $next($request, $response);
        
        else {
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "No tiene permisos para realizar esta acción.");
            $newResponse = $response->withJson($respuesta, 401);
            return $newResponse;
        }
    }

    public static function SumarOperacion($request, $response, $next) {
        $payload = $request->getAttribute("payload")["Payload"];
        $data = $payload->data;
        $nombre_operacion = $_SERVER["REQUEST_URI"];
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        $fecha = date('Y-m-d');
        
        try {
            $operacion = new App\Models\Operacion;        
            $operacion->id_empleado = $data->id;
            $operacion->operacion = $nombre_operacion;
            $operacion->fecha = $fecha;
            $operacion->save();
            return $next($request, $response);
        }
        catch(Exception $e) {
            $error = $e->getMessage();
            $mensaje = array("Estado" => "ERROR", "Mensaje" => "Error al guaradar operación", "Excepción" => $error);
            return $response->withJson($mensaje, 200);
        } 
    }
}

