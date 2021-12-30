<?php

namespace FacebookData;

use FacebookData\FBHelpers;

class FBFriends {
	public $FBDataRootDir = null;
	public $profile_photos_dir = null;


	public function __construct($FBDataRootDir) {
		if (is_dir($FBDataRootDir)) {
			$this->FBDataRootDir = $FBDataRootDir;
			$this->profile_photos_dir = $FBDataRootDir . '/profile_photos/';
		}
		return;
	}

    public function get()
    {
		$friend_list = new \stdClass();
        $inbox_dir = $this->FBDataRootDir . '/messages/inbox';
		if (is_dir($inbox_dir)) {
			$friend_info = array();
			$list = array_diff(scandir($inbox_dir), array('.', '..'));
			$i = 0;
			foreach($list as $name){
				if(is_dir($inbox_dir.'/'.$name)){
					$json_file = $inbox_dir.'/'.$name.'/'.'message_1.json';
					$jsonData = FBHelpers::fixed_json_data($json_file);
					$photo = $this->profile_photos_dir . 'default.png';
					$profile_photo_files = array_diff(scandir($this->profile_photos_dir), array('.', '..'));
					foreach ($profile_photo_files as $fd) {
						if (preg_match("/".$name."(.*)/", pathinfo($fd, PATHINFO_FILENAME)))
							$photo = $this->profile_photos_dir . $fd;
					}
					$friend_list->$i = (object)[
						'json_file' => $inbox_dir . '/' . $name . '/' . 'message_1.json',
						'dir_name' => $name,
						'name' => $jsonData->title,
						'profile_photo' => $photo,
					];
					$i++;
				}
			}
			return $friend_list;
			}
		return;
    }

}
