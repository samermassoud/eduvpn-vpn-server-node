#!/usr/bin/env php
<?php

/*
 * eduVPN - End-user friendly VPN.
 *
 * Copyright: 2016-2018, The Commons Conservancy eduVPN Programme
 * SPDX-License-Identifier: AGPL-3.0+
 */

require_once dirname(__DIR__).'/vendor/autoload.php';
$baseDir = dirname(__DIR__);

use SURFnet\VPN\Common\Config;
use SURFnet\VPN\Common\Http\Exception\InputValidationException;
use SURFnet\VPN\Common\Http\InputValidation;
use SURFnet\VPN\Common\HttpClient\CurlHttpClient;
use SURFnet\VPN\Common\HttpClient\Exception\ApiException;
use SURFnet\VPN\Common\HttpClient\ServerClient;
use SURFnet\VPN\Common\Logger;
use SURFnet\VPN\Node\Connection;

$logger = new Logger(
    basename($argv[0])
);

$envData = [];
try {
    $envKeys = [
        'INSTANCE_ID',
        'PROFILE_ID',
        'common_name',
        'time_unix',
        'ifconfig_pool_remote_ip',
        'ifconfig_pool_remote_ip6',
    ];

    // read environment variables
    foreach ($envKeys as $envKey) {
        $envData[$envKey] = getenv($envKey);
    }

    if (false === $instanceId = $envData['INSTANCE_ID']) {
        throw new InputValidationException('invalid "instance_id"');
    }
    $instanceId = InputValidation::instanceId($instanceId);
    $configDir = sprintf('%s/config/%s', $baseDir, $instanceId);
    $config = Config::fromFile(
        sprintf('%s/config.php', $configDir)
    );

    $serverClient = new ServerClient(
        new CurlHttpClient([$config->getItem('apiUser'), $config->getItem('apiPass')]),
        $config->getItem('apiUri')
    );

    $connection = new Connection($serverClient);
    $connection->connect($envData);
} catch (ApiException $e) {
    $logger->warning($e->getMessage());
    exit(1);
} catch (Exception $e) {
    $logger->error($e->getMessage());
    exit(1);
}
