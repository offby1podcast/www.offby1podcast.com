<?php
namespace OB1\Archive;

class Chapter {
	public function __construct(
		private string $timestamp,
		private string $title
	) {}

	public function getTimestamp():string {
		return $this->timestamp;
	}

	public function getSeconds():int {
		[$minutes, $seconds] = explode(":", $this->timestamp);
		return ($minutes * 60) + $seconds;
	}

	public function getTitle():string {
		return $this->title;
	}
}
