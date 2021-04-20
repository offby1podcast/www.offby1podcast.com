<?php
namespace OB1\Archive;

class InterestingLink {
	public function __construct(
		private string $title,
		private string $url,
		private string $person
	) { }
}
