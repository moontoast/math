<?php

/**
 * This file is part of the Moontoast\Math library
 *
 * Copyright 2013 Moontoast, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright 2013 Moontoast, Inc.
 * @license http://alphabase.moontoast.com/licenses/apache-2.0.txt Apache 2.0
 */

namespace Moontoast\Math;

/**
 * Shortcut class to generate an Immutable BigNumber
 *
 * @link http://www.php.net/bcmath
 */
class ImmutableBigNumber extends BigNumber
{

    public function __construct($number, $scale = null)
    {
        parent::__construct($number, $scale, false);
    }
}
