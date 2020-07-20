<?php

use Firebase\JWT\JWT;

class Token
{
    private static $clave = 'ClaveSuperSecreta@';
    private static $encriptacion = ['HS256'];
    
    public static function CrearToken($datos) {
        $ahora = time();
        $payload = array(
        	'iat' => $ahora,
            'exp' => $ahora + (60*60),
            'aud' => "usuario",
            'app' => "Comanda",
            'data' => $datos
        );
     
        return JWT::encode($payload, self::$clave);
    }
    
    public static function VerificarToken($token) {  
        $decodificado = [];

        if(empty($token) || $token == "")
            $decodificado = array("Estado" => "ERROR", "Mensaje" => "El token esta vacío");
            
        try {
            $payload = JWT::decode($token, self::$clave, self::$encriptacion);
            $decodificado = array("Estado" => "OK", "Mensaje" => "OK", "Payload" => $payload);
        }
        catch (Exception $e) {
            $mensaje = $e->getMessage();
            $decodificado = array("Estado" => "ERROR", "Mensaje" => $mensaje);
        }
        return $decodificado;
    }
}