<?php

/*
 * eduVPN - End-user friendly VPN.
 *
 * Copyright: 2016-2018, The Commons Conservancy eduVPN Programme
 * SPDX-License-Identifier: AGPL-3.0+
 */

namespace SURFnet\VPN\Node\Tests;

use PHPUnit\Framework\TestCase;
use SURFnet\VPN\Node\IP;

class IPTest extends TestCase
{
    public function testIPv4One()
    {
        $ip = new IP('192.168.1.0/24');
        $splitRange = $ip->split(1);
        $this->assertSame(1, count($splitRange));
        $this->assertSame('192.168.1.0/24', (string) $splitRange[0]);
    }

    public function testIPv4Two()
    {
        $ip = new IP('192.168.1.0/24');
        $splitRange = $ip->split(2);
        $this->assertSame(2, count($splitRange));
        $this->assertSame('192.168.1.0/25', (string) $splitRange[0]);
        $this->assertSame('192.168.1.128/25', (string) $splitRange[1]);
    }

    public function testIPv4Four()
    {
        $ip = new IP('192.168.1.0/24');
        $splitRange = $ip->split(4);
        $this->assertSame(4, count($splitRange));
        $this->assertSame('192.168.1.0/26', (string) $splitRange[0]);
        $this->assertSame('192.168.1.64/26', (string) $splitRange[1]);
        $this->assertSame('192.168.1.128/26', (string) $splitRange[2]);
        $this->assertSame('192.168.1.192/26', (string) $splitRange[3]);
    }

    public function testIPv6One()
    {
        $ip = new IP('1111:2222:3333:4444::/64');
        $splitRange = $ip->split(1);
        $this->assertSame(1, count($splitRange));
        $this->assertSame('1111:2222:3333:4444::/64', (string) $splitRange[0]);
    }

    public function testIPv6Two()
    {
        $ip = new IP('1111:2222:3333:4444::/64');
        $splitRange = $ip->split(2);
        $this->assertSame(2, count($splitRange));
        $this->assertSame('1111:2222:3333:4444::/68', (string) $splitRange[0]);
        $this->assertSame('1111:2222:3333:4444:1000::/68', (string) $splitRange[1]);
    }

    public function testIPv6Four()
    {
        $ip = new IP('1111:2222:3333:4444::/64');
        $splitRange = $ip->split(4);
        $this->assertSame(4, count($splitRange));
        $this->assertSame('1111:2222:3333:4444::/68', (string) $splitRange[0]);
        $this->assertSame('1111:2222:3333:4444:1000::/68', (string) $splitRange[1]);
        $this->assertSame('1111:2222:3333:4444:2000::/68', (string) $splitRange[2]);
        $this->assertSame('1111:2222:3333:4444:3000::/68', (string) $splitRange[3]);
    }
}
