<?php

function jwtResponse($token, $statusCode = 200)
{
    return jsonSuccess($token, $statusCode);
}



function jsonResponse($status, $data, $statusCode)
{
    return response()->json(array_merge([
        'status' => $status,
    ], $data), $statusCode);
}
function jsonSuccess($data = null, $statusCode = 200)
{
    return jsonResponse(true, ['data' => $data], $statusCode);
}
function jsonError($data = null, $statusCode = 400)
{
    return jsonResponse(false, ['errors' => $data], $statusCode);
}



function successResponse($message, $statusCode = 200)
{
    return jsonSuccess($message, $statusCode);
}



function errorResponse($message, $statusCode = 400)
{
    return jsonError($message, $statusCode);
}


function response200($message)
{
    return successResponse($message, 200);
}
function response200WithType($type, $message)
{
    return response200(['type' => $type, 'attributes' => $message]);
}
function response200WithTypeAndMessage($type, $message)
{
    return response200(array_merge(['type' => $type], $message));
}

function response201($message)
{
    return successResponse($message, 201);
}
function response201WithTypeAndMessage($type, $message)
{
    return response201(array_merge(['type' => $type], $message));
}

function response204($message)
{
    return successResponse($message, 204);
}


function response400($message)
{
    return  errorResponse($message, 400);
}

function response401($message)
{
    return  errorResponse($message, 401);
}

function response404($message)
{
    return  errorResponse($message, 404);
}

function response422($message)
{
    return  errorResponse($message, 422);
}

function response500($message)
{
    return  errorResponse($message, 500);
}
function response503($message)
{
    return  errorResponse($message, 503);
}




function throwException($message)
{
    throw new Exception($message);
}




////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
function idEncode($id)
{
    $hashids = new Hashids\Hashids(env('APP_KEY'), 6, 'ABCDEFGHJKMNPQRSTUVWXYZ123456789');
    return $hashids->encode($id);
}

function idDecode($id)
{
    $hashids = new Hashids\Hashids(env('APP_KEY'), 6, 'ABCDEFGHJKMNPQRSTUVWXYZ123456789');
    $decoded = $hashids->decode(strtoupper($id));
    if ($decoded) return $decoded[0] ?? null;
}
