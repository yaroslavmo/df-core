<?php
/**
 * This file is part of the DreamFactory Rave(tm)
 *
 * DreamFactory Rave(tm) <http://github.com/dreamfactorysoftware/rave>
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
namespace DreamFactory\Rave\Models;

/**
 * App
 *
 * @property integer $id
 * @property string  $name
 * @property string  $api_key
 * @property string  $description
 * @property boolean $is_active
 * @property integer $role_id
 * @property string  $created_date
 * @property string  $last_modified_date
 * @method static \Illuminate\Database\Query\Builder|App whereId( $value )
 * @method static \Illuminate\Database\Query\Builder|App whereName( $value )
 * @method static \Illuminate\Database\Query\Builder|App whereApiKey( $value )
 * @method static \Illuminate\Database\Query\Builder|App whereIsActive( $value )
 * @method static \Illuminate\Database\Query\Builder|App whereRoleId( $value )
 * @method static \Illuminate\Database\Query\Builder|App whereCreatedDate( $value )
 * @method static \Illuminate\Database\Query\Builder|App whereLastModifiedDate( $value )
 */
class App extends BaseSystemModel
{
    protected $table = 'app';

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'type',
        'url',
        'storage_service_id',
        'storage_container',
        'import_url',
        'requires_fullscreen',
        'allow_fullscreen_toggle',
        'toggle_location',
        'role_id'
    ];

    public static function generateApiKey( $name )
    {
        $string = gethostname() . $name . time();
        $key = hash( 'sha256', $string );

        return $key;
    }

    /**
     * @param       $record
     * @param array $params
     *
     * @return array
     */
    protected static function createInternal( $record, $params = [ ] )
    {
        try
        {
            $model = static::create( $record );
            $apiKey = static::generateApiKey($model->name);
            $model->api_key = $apiKey;
            $model->save();
        }
        catch ( \PDOException $e )
        {
            throw $e;
        }

        return static::buildResult( $model, $params );
    }
}