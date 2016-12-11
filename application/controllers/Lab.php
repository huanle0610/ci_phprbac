<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lab extends CI_Controller {

	public function getJsonP()
	{
		$callback = $this->input->get('callback');

		// Create the output object.
		$output = array('a' => 'Apple', 'b' => 'Banana');

		//start output
		if ($callback) {
			header('Content-Type: text/javascript');
			echo $callback . '(' . json_encode($output) . ');';
		} else {
			header('Content-Type: application/x-json');
			echo json_encode($output);
		}
	}
}
