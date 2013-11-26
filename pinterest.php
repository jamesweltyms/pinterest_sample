<?php

class rssPinterest {

	private $username;
	private $url_domain;
	private $url_dir;
	private $full_url;
	private $rss_data;
	private $xml_data;
	public $post_array = array();

	public function __construct($username = null) {
		if ((!isset($username)) or ($username == false)) {
			throw new pinException('Username cannot be null');
		}

		$this->username = $username;
		$this->url_domain = 'http://www.pinterest.com/';
		$this->url_dir = '/feed.rss';

		$this->run();
	}

	private function run() {
		$this->constructUrl();
		$this->getData();
		$this->verifyData();
		$this->formatXml();
		$this->loopData();
	}

	private function constructUrl() {
		$this->full_url = $this->url_domain . $this->username . $this->url_dir;
	}

	private function getData() {
		$this->rss_data = file_get_contents($this->full_url);
	}

	private function verifyData() {
		if ($this->rss_data === false) {
			throw new pinException('Unable to retrieve feed; possibly bad username');
		}
	}

	private function formatXml() {
		$this->xml_data = new SimpleXmlElement($this->rss_data);
	}

	private function loopData() {
		foreach ($this->xml_data->channel->item as $item) {
			$post = array(
				'title' => $item->title,
				'description' => $item->description,
				'link' => $item->link,
				'guid' => $item->guid,
				'pubDate' => $item->pubDate
			);
			$this->post_array[] = $post;
		}
	}

	public function __destruct() {
		unset($this->username);
		unset($this->url_domain);
		unset($this->url_dir);
		unset($this->full_url);
		unset($this->rss_data);
		unset($this->xml_data);
		unset($this->post_array);
	}

}

class pinException extends Exception{}

?>
