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
 * Unless required by Userlicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace DreamFactory\Core\Models;

use DreamFactory\Core\Exceptions\BadRequestException;

/**
 * RoleServiceAccess
 *
 * @property integer    $id
 * @property integer    $role_id
 * @property integer    $service_id
 * @property string     $component
 * @property integer    $verb_mask
 * @property integer    $requestor_mask
 * @property array      $filters
 * @property string     $filter_op
 * @property string     $created_date
 * @property string     $last_modified_date
 * @method static \Illuminate\Database\Query\Builder|RoleServiceAccess whereId( $value )
 * @method static \Illuminate\Database\Query\Builder|RoleServiceAccess whereRoleId( $value )
 * @method static \Illuminate\Database\Query\Builder|RoleServiceAccess whereServiceId( $value )
 * @method static \Illuminate\Database\Query\Builder|RoleServiceAccess whereCreatedDate( $value )
 * @method static \Illuminate\Database\Query\Builder|RoleServiceAccess whereLastModifiedDate( $value )
 */
class RoleServiceAccess extends BaseSystemModel
{
    protected $table = 'role_service_access';

    protected $guarded = [ 'id' ];

    protected $casts = [ 'filters' => 'array' ];

    public static function boot()
    {
        parent::boot();

        static::saving(
            function(RoleServiceAccess $rsa)
            {
                if(1 === $rsa->service_id && ('*' === $rsa->component || 'admin' === $rsa->component))
                {
                    throw new BadRequestException('* and/or admin is not allowed on system service.');
                }
            }
        );
    }
}