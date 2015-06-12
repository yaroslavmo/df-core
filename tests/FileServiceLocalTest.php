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

class FileServiceLocalTest extends \DreamFactory\Core\Testing\FileServiceTestCase
{
    protected static $staged = false;

    protected $serviceId = 'files';

    public function stage()
    {
        parent::stage();

        if(!$this->serviceExists('files'))
        {
            \DreamFactory\Core\Models\Service::create(
                [
                    "name"        => "files",
                    "label"       => "Local file service",
                    "description" => "Local file service for unit test",
                    "is_active"   => 1,
                    "type"        => "local_file"
                ]
            );
        }
    }
}