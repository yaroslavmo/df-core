<?php

//Todo:Any better way to do this?
//Treat merge as patch
$method = Request::getMethod();
if (\DreamFactory\Library\Utility\Enums\Verbs::MERGE === strtoupper($method)) {
    Request::setMethod(\DreamFactory\Library\Utility\Enums\Verbs::PATCH);
}

$resourcePathPattern = '[0-9a-zA-Z-_@&\#\!=,:;\/\^\$\.\|\{\}\[\]\(\)\*\+\? ]+';
$servicePattern = '[_0-9a-zA-Z-]+';

Route::group(
    ['namespace' => 'DreamFactory\Core\Http\Controllers'],
    function () use ($resourcePathPattern, $servicePattern){

        Route::group(
            ['prefix' => 'api'],
            function () use ($resourcePathPattern, $servicePattern){
                Route::get('{version}/', 'RestController@index');
                Route::get('{version}/{service}/{resource?}', 'RestController@handleGET')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
                Route::post('{version}/{service}/{resource?}', 'RestController@handlePOST')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
                Route::put('{version}/{service}/{resource?}', 'RestController@handlePUT')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
                Route::patch('{version}/{service}/{resource?}', 'RestController@handlePATCH')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
                Route::delete('{version}/{service}/{resource?}', 'RestController@handleDELETE')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
            }
        );

        Route::group(
            ['prefix' => 'rest'],
            function () use ($resourcePathPattern, $servicePattern){
                Route::get('/', 'RestController@index');
                Route::get('{service}/{resource?}', 'RestController@handleV1GET')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
                Route::post('{service}/{resource?}', 'RestController@handleV1POST')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
                Route::put('{service}/{resource?}', 'RestController@handleV1PUT')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
                Route::patch('{service}/{resource?}', 'RestController@handleV1PATCH')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
                Route::delete('{service}/{resource?}', 'RestController@handleV1DELETE')->where(
                    ['service' => $servicePattern, 'resource' => $resourcePathPattern]
                );
            }
        );

        Route::get('{storage}/{path}', 'StorageController@handleGET')->where(
            ['storage' => $servicePattern, 'path' => $resourcePathPattern]
        );
    }
);