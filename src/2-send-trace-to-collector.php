<?php

// Include the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProviderFactory;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;


putenv('OTEL_EXPORTER_OTLP_ENDPOINT=http://54.161.205.67:4318/v1/traces');  // SigNoz OTel collector's path
putenv('OTEL_EXPORTER_OTLP_PROTOCOL=http/protobuf');


$factory = new TracerProviderFactory();
$tracerProvider = $factory->create();
$tracer = $tracerProvider->getTracer('io.signoz.php.example');


$root = $span = $tracer->spanBuilder('root')->startSpan();
$rootScope = $span->activate();


try  {
	for ($i = 0; $i < 3; $i++) {
    // start a span, register some events
    $span = $tracer->spanBuilder('loop-' . $i)->startSpan();

    $span->setAttribute('remote_ip', '1.2.3.4')
        ->setAttribute('country', 'USA');

    $capacityOrModeFlag = 1024; // Replace 1024 with the appropriate integer based on the library's documentation or defaults

$span->addEvent('found_login' . $i, new Attributes([
    'id' => $i,
    'username' => 'otuser' . $i,
], $capacityOrModeFlag));

$span->addEvent('generated_session', new Attributes([
    'id' => md5((string) microtime(true)),
], $capacityOrModeFlag));



    $span->end();
    }
}finally {
    // Ensure the root span is ended and the scope is detached
    $root->end();
    $rootScope->detach();
}


