<?php

/*
 * Vercel serverless entrypoint.
 *
 * vercel.json routes every non-static request here, and this file
 * forwards handling to the standard Laravel front controller.
 *
 * TEMPORARY DIAGNOSTIC BRANCH -- revert this block once the runtime
 * failure is identified. Gated on an unguessable path so scanners
 * cannot trigger it.
 */

const DIAG_TOKEN = '2aba4253f6b1e0427a741a553a763f84d482ab28aaaadfca';

$root = __DIR__.'/..';

if (str_contains($_SERVER['REQUEST_URI'] ?? '', DIAG_TOKEN)) {
    header('Content-Type: text/plain; charset=utf-8');

    $out = [];
    $line = function (string $k, $v = '') use (&$out) { $out[] = $k.': '.$v; };

    $line('php_version', PHP_VERSION);
    $line('sapi', PHP_SAPI);
    $line('cwd', getcwd());
    $line('docroot', $_SERVER['DOCUMENT_ROOT'] ?? '(unset)');
    $line('request_uri', $_SERVER['REQUEST_URI'] ?? '(unset)');
    $line('memory_limit', ini_get('memory_limit'));
    $line('open_basedir', ini_get('open_basedir') ?: '(none)');
    $line('disable_functions', ini_get('disable_functions') ?: '(none)');

    $line('', '');
    $line('--- paths (relative to project root) ---');
    foreach ([
        'vendor/autoload.php',
        'bootstrap/app.php',
        'bootstrap/cache',
        'bootstrap/cache/packages.php',
        'bootstrap/cache/services.php',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/logs',
        'resources/views/astro/welcome.blade.php',
        'public/index.php',
    ] as $rel) {
        $line($rel, file_exists($root.'/'.$rel) ? 'exists' : 'MISSING');
    }

    $line('', '');
    $line('--- writability ---');
    foreach ([
        'bootstrap/cache',
        'storage/framework',
        '/tmp',
    ] as $rel) {
        $line($rel, is_writable($root.'/'.$rel) ? 'writable' : 'NOT writable');
    }

    $line('', '');
    $line('--- extensions Laravel needs ---');
    foreach (['ctype', 'curl', 'dom', 'fileinfo', 'json', 'mbstring', 'openssl', 'pdo', 'tokenizer', 'xml'] as $ext) {
        $line('ext:'.$ext, extension_loaded($ext) ? 'yes' : 'MISSING');
    }

    $line('', '');
    $line('--- boot trace ---');

    try {
        require $root.'/vendor/autoload.php';
        $line('require autoload', 'ok');

        $app = require $root.'/bootstrap/app.php';
        $line('require bootstrap/app.php', 'ok');

        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        $line('resolve HTTP kernel', 'ok');

        $response = $kernel->handle(Illuminate\Http\Request::capture());
        $line('handle() status', $response->getStatusCode());
        $line('handle() content-type', $response->headers->get('Content-Type'));
        $line('handle() body bytes', strlen($response->getContent()));
        $line('handle() body head', substr(preg_replace('/\s+/', ' ', (string) $response->getContent()), 0, 300));
    } catch (\Throwable $e) {
        $line('THROWN', get_class($e));
        $line('message', $e->getMessage());
        $line('at', $e->getFile().':'.$e->getLine());
        $line('trace', substr($e->getTraceAsString(), 0, 1500));
    }

    echo implode("\n", $out)."\n";
    exit;
}

require __DIR__.'/../public/index.php';
