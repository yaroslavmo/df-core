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

namespace DreamFactory\Core\Resources\System;

class Constant extends ReadOnlySystemResource
{
    protected function handleGET()
    {
        // todo need some fancy reflection of enum classes in the system we want to expose
        $resources = [ ];
        if ( empty( $this->_resource ) )
        {
            $resources = [];
        }
        else
        {
            switch ( $this->_resource )
            {
                default;
                    break;
            }
        }

        return [ 'resource' => $resources ];
    }

    public function getApiDocInfo()
    {
        $path = '/' . $this->getServiceName() . '/' . $this->getFullPathName();
        $eventPath = $this->getServiceName() . '.' . $this->getFullPathName('.');
        $_constant = [ ];

        $_constant['apis'] = [
            [
                'path'        => $path,
                'operations'  => [
                    [
                        'method'           => 'GET',
                        'summary'          => 'getConstants() - Retrieve all platform enumerated constants.',
                        'nickname'         => 'getConstants',
                        'type'             => 'Constants',
                        'event_name'       => $eventPath . '.list',
                        'responseMessages' => [
                            [
                                'message' => 'Bad Request - Request does not have a valid format, all required parameters, etc.',
                                'code'    => 400,
                            ],
                            [
                                'message' => 'Unauthorized Access - No currently valid session available.',
                                'code'    => 401,
                            ],
                            [
                                'message' => 'System Error - Specific reason is included in the error message.',
                                'code'    => 500,
                            ],
                        ],
                        'notes'            => 'Returns an object containing every enumerated type and its constant values',
                    ],
                ],
                'description' => 'Operations for retrieving platform constants.',
            ],
            [
                'path'        => $path . '/{type}',
                'operations'  => [
                    [
                        'method'           => 'GET',
                        'summary'          => 'getConstant() - Retrieve one constant type enumeration.',
                        'nickname'         => 'getConstant',
                        'type'             => 'Constant',
                        'event_name'       => $eventPath . '.read',
                        'parameters'       => [
                            [
                                'name'          => 'type',
                                'description'   => 'Identifier of the enumeration type to retrieve.',
                                'allowMultiple' => false,
                                'type'          => 'string',
                                'paramType'     => 'path',
                                'required'      => true,
                            ],
                        ],
                        'responseMessages' => [
                            [
                                'message' => 'Bad Request - Request does not have a valid format, all required parameters, etc.',
                                'code'    => 400,
                            ],
                            [
                                'message' => 'Unauthorized Access - No currently valid session available.',
                                'code'    => 401,
                            ],
                            [
                                'message' => 'System Error - Specific reason is included in the error message.',
                                'code'    => 500,
                            ],
                        ],
                        'notes'            => 'Returns , all fields and no relations are returned.',
                    ],
                ],
                'description' => 'Operations for retrieval individual platform constant enumerations.',
            ],
        ];

        $_constant['models'] = [
            'Constants' => [
                'id'         => 'Constants',
                'properties' => [
                    'type_name' => [
                        'type'  => 'array',
                        'items' => [
                            '$ref' => 'Constant',
                        ],
                    ],
                ],
            ],
            'Constant'  => [
                'id'         => 'Constant',
                'properties' => [
                    'name' => [
                        'type'  => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ];

        return $_constant;
    }
}