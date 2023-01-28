<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *         http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
// START SNIPPET: doxia
require_once __DIR__.'/../../main/php/Logger.php';
Logger::configure(__DIR__.'/../resources/renderer_default.properties');

class Person implements \Stringable {
    public $firstName = 'John';
    public $lastName = 'Doe';

    public function __toString() : string {
        return $this->lastName . ', ' . $this->firstName;
    }
}

$person = new Person();

$logger = Logger::getRootLogger();
$logger->debug("Now comes the current MyClass object:");
$logger->debug($person);
