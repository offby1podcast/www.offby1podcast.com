<?php
namespace OB1\Page;

use Gt\WebEngine\Logic\Page;
use OB1\Archive\JsonArchive;

class RssPage extends Page {
	public function go():void {
		header("Content-type: application/json");
		$json = new JsonArchive();
		echo json_encode($json->jsonSerialize());
		exit;
	}
}
