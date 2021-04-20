<?php
namespace OB1\Archive;

class Chapter {
	public function __construct(
		private string $timestamp,
		private string $title
	) {}
}
