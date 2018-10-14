<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\NewsController;

// Routes News
$app->get('/news', NewsController::class . ':list');
$app->get('/news/{id}', NewsController::class . ':detail');
$app->post('/news', NewsController::class . ':add');
$app->put('/news/{id}', NewsController::class . ':edit');
$app->delete('/news/{id}', NewsController::class . ':delete');