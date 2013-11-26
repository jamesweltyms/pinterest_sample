<?php

class SampleController extends AppController {

	var $name = 'Sample';
	var $uses = array('Post');

	function getPin() {
		$this->autoRender = false;

		App::import('Vendor', 'thisPin', array('file' => 'pinterest.php'));

		$username = 'chapmanu';

		try {
			$thisPin = new rssPinterest($username);
		} catch(pinterest $e) {
			var_dump($e);
		}

		$post_array = $thisPin->post_array;

		foreach ($post_array as $p) {
			$this->Post->create();
			$this->Post->set(array(
				'username' => $username,
				'title' => json_encode($p['title']),
				'description' => json_encode($p['description']),
				'link' => json_encode($p['link']),
				'pubDate' => json_encode($p['pubDate']),
				'guid' => json_encode($p['guid'])
			));
			$this->Post->save();
		}

		unset($thisPin);

		echo 'done';

	}

}
?>
