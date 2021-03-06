<?php

namespace DigitalRisks\LaravelEventStore\Tests;

use DigitalRisks\LaravelEventStore\Console\Commands\EventStoreWorkerThread;
use DigitalRisks\LaravelEventStore\TestUtils\Traits\MakesEventRecords as TraitsMakesEventRecords;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use TiMacDonald\Log\LogFake;

class LoggerTest extends TestCase
{
    use TraitsMakesEventRecords;

    public function test_it_logs_an_event()
    {
        // Arrange.
        Log::swap(new LogFake);

        $worker = resolve(EventStoreWorkerThread::class);
        $event = $this->makeEventRecord('test_event', ['hello' => 'world']);

        // Act.
        $worker->dispatch($event);

        // Assert.
        Log::assertLogged('info', function ($message, $context) {
            $this->assertStringContainsString("/streams/test-stream/0", $message);
            $this->assertEquals(['type' => 'test_event', 'hasListeners' => false], $context);

            return true;
        });
    }

    public function test_it_logs_an_event_with_a_listener()
    {
        // Arrange.
        Log::swap(new LogFake);

        $worker = resolve(EventStoreWorkerThread::class);
        $event = $this->makeEventRecord('test_event', ['hello' => 'world']);
        Event::listen('test_event', function () {
        });

        // Act.
        $worker->dispatch($event);

        // Assert.
        Log::assertLogged('info', function ($message, $context) {
            $this->assertStringContainsString("/streams/test-stream/0", $message);
            $this->assertEquals(['type' => 'test_event', 'hasListeners' => true], $context);

            return true;
        });
    }
}
