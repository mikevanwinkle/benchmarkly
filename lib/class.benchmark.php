<?php
namespace Benchmarkly;

interface Benchmark {
	public function test();
	public function run();
	public function report();
}
