<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProxySecurityTest extends TestCase
{
    public function test_forwarded_headers_from_an_untrusted_client_are_ignored(): void
    {
        config(['trustedproxy.proxies' => []]);
        $this->registerProbe();

        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.10'])
            ->withHeaders([
                'X-Forwarded-For' => '203.0.113.20',
                'X-Forwarded-Proto' => 'https',
                'X-Forwarded-Host' => 'attacker.example',
            ])
            ->getJson('/_test/proxy')
            ->assertOk()
            ->assertExactJson(['ip' => '198.51.100.10', 'secure' => false, 'host' => 'localhost']);
    }

    public function test_explicitly_trusted_proxy_can_supply_ip_and_https_but_not_host(): void
    {
        config(['trustedproxy.proxies' => ['192.0.2.10']]);
        $this->registerProbe();

        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.10'])
            ->withHeaders([
                'X-Forwarded-For' => '203.0.113.20',
                'X-Forwarded-Proto' => 'https',
                'X-Forwarded-Host' => 'attacker.example',
            ])
            ->getJson('/_test/proxy')
            ->assertOk()
            ->assertExactJson(['ip' => '203.0.113.20', 'secure' => true, 'host' => 'localhost']);
    }

    public function test_an_unlisted_client_cannot_impersonate_a_configured_proxy(): void
    {
        config(['trustedproxy.proxies' => ['192.0.2.10']]);
        $this->registerProbe();

        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.10'])
            ->withHeaders(['X-Forwarded-For' => '203.0.113.20', 'X-Forwarded-Proto' => 'https'])
            ->getJson('/_test/proxy')
            ->assertOk()
            ->assertExactJson(['ip' => '198.51.100.10', 'secure' => false, 'host' => 'localhost']);
    }

    private function registerProbe(): void
    {
        Route::get('/_test/proxy', fn (Request $request): array => [
            'ip' => $request->ip(),
            'secure' => $request->isSecure(),
            'host' => $request->getHost(),
        ]);
    }
}
