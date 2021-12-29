<?php

class FBMessages {
    public $participants;
    public $messages;
    public $title;
    public $is_still_participant;
    public $thread_type;
    public $thread_path;
    public $magic_words;

	public function __construct($json_file) {
		if (is_file($json_file)) {
			$jsonData = fixed_json_data($json_file);
			$this->participants = $jsonData->participants;
			$this->messages = $jsonData->messages;
			$this->title = $jsonData->title;
			$this->is_still_participant = $jsonData->is_still_participant;
			$this->thread_type = $jsonData->thread_type;
			$this->thread_path = $jsonData->thread_path;
			$this->magic_words = $jsonData->magic_words;
		}
		return;
	}

	public function message_type($message_number) {
		$nr = ($message_number - 1);
		if (($nr > 0) && ($nr <= count($this->messages))) {
			$side = null;
			$type = null;
			if (isset($this->messages)) {
				$message = (array)$this->messages[$nr];
				$message["sender_name"] == $this->title ? $side = "left": $side = "right";
				if (!isset($message["content"]) && !isset($message["videos"]) && !isset($message["photos"]) && !isset($message["audio_files"]) && !isset($message["gifs"]) && !isset($message["share"])) $type = null;
				if (isset($message["content"]) && !isset($message["videos"]) && !isset($message["photos"]) && !isset($message["audio_files"]) && !isset($message["gifs"]) && !isset($message["share"])) $type = 'message';
				if (isset($message["content"]) && isset($message["share"])) $type = 'share';
				if (!isset($message["video"]) && !isset($message["photos"]) && isset($message["audio_files"])) $type = 'audio_files';
				if (!isset($message["video"]) && isset($message["photos"]) && !isset($message["audio_files"])) $type = 'photos';
				if (isset($message["videos"]) && !isset($message["photos"]) && !isset($message["audio_files"])) $type = 'videos';
				if (isset($message["gifs"])) $type = 'gifs';
				if (isset($message["sticker"])) $type = 'sticker';
				if (isset($side) && isset($type)) {
					return $type . '-' . $side;
				}else{
					return null;
				}
			}
		}
		return;
	}
}

function fixed_json_data($json_file) {
	function replace_with_byte($match) {
		return chr(hexdec($match[1]));
	}
	if (is_file($json_file)) { 
		$json_data = file_get_contents($json_file);
		$data = preg_replace_callback('/\\\\u00([a-f0-9]{2})/', 'replace_with_byte', $json_data);
 		$json_data = json_decode($data, false);
		return $json_data;
	}
	return;
}