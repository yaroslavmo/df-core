<?php
/**
 * This file is part of the DreamFactory(tm) Core
 *
 * DreamFactory(tm) Core <http://github.com/dreamfactorysoftware/df-core>
 * Copyright 2012-2014 DreamFactory Software, Inc. <support@dreamfactory.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace DreamFactory\Core\Utility;

use DreamFactory\Core\Exceptions\ForbiddenException;
use DreamFactory\Core\Exceptions\NotFoundException;
use DreamFactory\Core\Models\Service;
use DreamFactory\Core\Services\BaseRestService;

/**
 * Class ServiceHandler
 *
 * @package DreamFactory\Core\Utility
 */
class ServiceHandler
{
    /**
     * @param $name
     *
     * @return BaseRestService
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public static function getService( $name )
    {
        $name = strtolower( trim( $name ) );

        $service = Service::whereName( $name )->get()->first();

        if ( empty( $service ) )
        {
            throw new NotFoundException( "Could not find a service for $name" );
        }

        return static::getServiceInternal( $service );
    }

    public static function getServiceById( $id )
    {
        $service = Service::find( $id );

        if ( empty( $service ) )
        {
            throw new NotFoundException( "Could not find a service for ID $id" );
        }

        return static::getServiceInternal( $service );
    }

    protected static function getServiceInternal( $service )
    {
        if ( $service instanceof Service )
        {
            if ( $service->is_active )
            {
                $serviceClass = $service->serviceType()->first()->class_name;
                $settings = $service->toArray();

                return new $serviceClass( $settings );
            }

            throw new ForbiddenException( "Service $service->name is inactive." );
        }

        throw new NotFoundException( "Could not find a service for $service->name." );
    }

    /**
     * @param null|string $version
     * @param             $service
     * @param null        $resource
     *
     * @return mixed
     * @throws NotFoundException
     */
    public static function processRequest( $version, $service, $resource = null )
    {
        $request = new ServiceRequest();
        $request->setApiVersion( $version );

        return self::getService( $service )->handleRequest( $request, $resource );
    }

    /**
     * @param bool $include_properties
     *
     * @return array
     */
    public static function listServices( $include_properties = false )
    {
        $services = Service::available( $include_properties );

        return [ 'service' => $services ];

    }
}