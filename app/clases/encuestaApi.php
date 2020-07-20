<?php

class EncuestaApi {
    // public function RegistrarEncuesta($request, $response, $args)
    // {
    //     $parametros = $request->getParsedBody();
    //     $codigoMesa = $parametros['codigoMesa'];
    //     $puntuacionMesa = $parametros['puntuacionMesa'];
    //     $puntuacionRestaurante = $parametros['puntuacionRestaurante'];
    //     $puntuacionMozo = $parametros['puntuacionMozo'];
    //     $puntuacionCocinero = $parametros['puntuacionCocinero'];
    //     $comentario = $parametros['comentario'];

    //     if(strlen($comentario) > 66)
    //     {
    //         $mensaje = array("Estado" => "ERROR", "Mensaje" => "El comentario no puede superar los 66 caracteres");
    //         return $response->withJson($mensaje, 200);
    //     }

    //     $encuesta = new App\Models\Encuesta;
    //     $mesa = new App\Models\Mesa;

    //     $mesaActual = $mesa->where('codigo', '=', $codigoMesa)->first();

    //     if($mesaActual)
    //     {
    //         try
    //         {
    //             date_default_timezone_set("America/Argentina/Buenos_Aires");
    //             $fecha = date('Y-m-d');
    //             $encuesta->fecha = $fecha;
    //             $encuesta->codigoMesa = $codigoMesa;
    //             $encuesta->puntuacionMesa = $puntuacionMesa;
    //             $encuesta->puntuacionRestaurante = $puntuacionRestaurante;
    //             $encuesta->puntuacionMozo = $puntuacionMozo;
    //             $encuesta->puntuacionCocinero = $puntuacionCocinero;
    //             $encuesta->comentario = $comentario;
    //             $encuesta->save();
    //             $mensaje = array("Estado" => "Ok", "Mensaje" => "Encuesta registrada");
    //         }
    //         catch(Exception $e)
    //         {
    //             $error = $e->getMessage();
    //             $mensaje = array("Estado" => "ERROR", "Mensaje" => $error);
    //         }
    //     }
    //     else
    //     {
    //         $mensaje = array("Estado" => "ERROR", "Mensaje" => "Codigo de mesa inexistente");
    //     }
    //     return $response->withJson($mensaje, 200);
    // }
}

