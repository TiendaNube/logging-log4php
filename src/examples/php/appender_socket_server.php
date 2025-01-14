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
Logger::configure(__DIR__.'/../resources/appender_socket_server.properties');

require_once 'Net/Server.php';
require_once 'Net/Server/Handler.php';

class Net_Server_Handler_Log extends Net_Server_Handler {

        private $hierarchy;

        function onStart() {
                $this->hierarchy = Logger::getRootLogger();
        }

        function onReceiveData($clientId = 0, $data = "") {
                $events = $this->getEvents($data);
                foreach($events as $event) {
                        $root = $this->hierarchy->getRootLogger();
                        if($event->getLoggerName() === 'root') {
                            $root->callAppenders($event);
                        } else {
                             $loggers = $this->hierarchy->getCurrentLoggers();
                                foreach($loggers as $logger) {
                                        $root->callAppenders($event);
                                        $appenders = $logger->getAllAppenders();
                                        foreach($appenders as $appender) {
                                                $appender->doAppend($event);
                                        }
                                }
                        }
                }
        }

        function getEvents($data) {
                if (preg_match('/^<log4php:event/', (string) $data)) {
                    throw new Exception("Please use 'log4php.appender.default.useXml = false' in appender_socket.properties file!");
                }
                preg_match('/^(O:\d+)/', (string) $data, $parts);
                $events = explode($parts[1], (string) $data);
                array_shift($events);
                $size = count($events);
                for($i=0; $i<$size; $i++) {
                        $events[$i] = unserialize($parts[1].$events[$i]);
                }
                return $events;
        }
}

$host = 'localhost';
$port = 4242;
$server = Net_Server::create('sequential', $host, $port);
$handler = new Net_Server_Handler_Log();
$server->setCallbackObject($handler);
$server->start();
