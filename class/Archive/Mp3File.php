<?php
namespace OB1\Archive;

use Gt\WebEngine\FileSystem\Path;
use SplFileObject;
use wapmorgan\Mp3Info\Mp3Info;

class Mp3File extends Mp3Info {
	public function __construct(
		private int $episodeNumber
	) {
		$padded = str_pad($this->episodeNumber, 3, "0", STR_PAD_LEFT);
		parent::__construct(Path::getDataDirectory() . "/$padded.mp3");
	}

	public function getByteLength():int {
		return $this->_fileSize;
	}

	public function getDuration():string {
		return floor($this->duration / 60) . ":" . floor($this->duration % 60);
	}
}
