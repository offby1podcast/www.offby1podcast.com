<?php
namespace OB1\Page;

use Gt\WebEngine\Logic\Page;

class _CommonPage extends Page {
	public function go():void {
		$this->copyrightYear();
	}

	private function copyrightYear():void {
		$this->document->bindKeyValue("year", date("Y"));
	}
}
