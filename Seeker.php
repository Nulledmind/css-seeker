<?php

include 'Seeker/Base.php';

class Seeker extends Base {

	public $html;
	public $js;

	function __construct() {
		parent::__construct();
		$dom = new DOMDocument();
		$this->html = $dom;
	}

	public function load($html) {
		$this->html->loadHTML($html);
	}

	public function getLinks() {
		foreach ($this->html->getElementsByTagName('a') as $link) {
			return $link->getAttribute('href');
		}
	}

}

$seek = new Seeker();
$seek->load('<html><head></head><body><a href="#DeezNUTS">Lol</a></body></html>');

echo $seek->getLinks();
