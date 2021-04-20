<?php
namespace OB1\Page;

use Gt\WebEngine\Logic\Page;

class LinksPage extends Page {
	public function go():void {
		$this->ensureSort();
	}

	private function ensureSort():void {
		if(!$this->input->contains("sort")) {
			$this->redirect("?sort=date");
		}
	}
}
