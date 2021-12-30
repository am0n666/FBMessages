<?php

namespace FacebookData;

use FacebookData\FBHelpers;

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
			$jsonData = FBHelpers::fixed_json_data($json_file);
			$this->participants = $jsonData->participants;
			$this->messages = array_reverse($jsonData->messages);
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
