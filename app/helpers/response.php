<?php

function jwtResponse($token, $statusCode = 200)
{
    return jsonSuccess($token, $statusCode);
}



function jsonResponse($status, $data, $statusCode)
{
    return response()->json([
        'status' => $status,
        'data' => $data
    ], $statusCode);
}
function jsonSuccess($data = null, $statusCode = 200)
{
    return jsonResponse(true, ['success' => $data], $statusCode);
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

function response400($message)
{
    return  errorResponse($message, 400);
}

function response401($message)
{
    return  errorResponse($message, 401);
}
function response422($message)
{
    return  errorResponse($message, 422);
}

function response500($message)
{
    return  errorResponse($message, 500);
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
