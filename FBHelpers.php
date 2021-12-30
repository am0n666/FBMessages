<?php

namespace FacebookData;

class FBHelpers {

	public static function fixed_json_data($json_file) {
		if (is_file($json_file)) { 
			$json_data = file_get_contents($json_file);
			$data = preg_replace_callback('/\\\\u00([a-f0-9]{2})/', function ($m) { return chr(hexdec($m[1])); }, $json_data);
			$json_data = json_decode($data, false);
			return $json_data;
		}
		return;
	}

	public static function timestampToDate( $timestamp_ms , $date_type = 'message_date', $lang = 'pl') {
		$months_short				= array("Jan" => "sty", "Feb" => "lut", "Mar" => "mar", "Apr" => "kwi", "May" => "maj", "Jun" => "cze", "Jul" => "lip", "Aug" => "sie", "Sep" => "wrz", "Oct" => "paź", "Nov" => "lis", "Dec" => "gru");
		$months_full				= array("January" => "styczeń", "February" => "luty", "March" => "marzec", "April" => "kwiecień", "May" => "maj", "June" => "czerwiec", "July" => "lipiec", "August" => "sierpień", "September" => "wrzesień", "October" => "październik", "November" => "listopad", "December" => "grudzień");
		$months_full_2				= array("January" => "stycznia", "February" => "lutego", "March" => "marca", "April" => "kwietnia", "May" => "maja", "June" => "czerwca", "July" => "lipca", "August" => "sierpnia", "September" => "września", "October" => "października", "November" => "listopada", "December" => "grudnia");
	
		$days_of_the_week_short		= array("Mon"=>"pon", "Tue"=>"wto", "Wed"=>"śro", "Thu"=>"czw", "Fri"=>"piąt", "Sat"=>"sob", "Sun"=>"niedz");
		$days_of_the_week_full		= array("Monday"=>"poniedziałek", "Tuesday"=>"wtorek", "Wednesday"=>"środa", "Thursday"=>"czwartek", "Friday"=>"piątek", "Saturday"=>"sobota", "Sunday"=>"niedziela");
	
		$date = new \DateTime();
		$date->setTimestamp($timestamp_ms / 1000);
	
		switch ($lang) {
			case "pl":
				if ($date_type == 'message_date') {
					$_day = $date->format("d");
					$_month = $months_short[date_format($date, 'M')];
					$_date_ = ($_day.' '.$_month.' '.$date->format("Y, H:i"));
				}
				if ($date_type == 'message_span_date') {
					$_day_name_full = $days_of_the_week_full[date_format($date, 'l')];
					$_day = $date->format("d");
					$_month_name_full = $months_full_2[date_format($date, 'F')];
					$_date_ = ($_day_name_full.', '.$_day.' '.$_month_name_full.' '.$date->format("Y").' o '.$date->format("H:i"));
				}
				return $_date_;
				break;
			case "en":
				if ($date_type == 'message_date') {
					$_day = $date->format("d");
					$_month = mb_strtolower(date_format($date, 'M'));
					$_date_ = ($_day.' '.$_month.' '.$date->format("Y, H:i"));
				}
				if ($date_type == 'message_span_date') {
					$_day_name_full = mb_strtolower(date_format($date, 'l'));
					$_day = $date->format("d");
					$_month_name_full = mb_strtolower(date_format($date, 'F'));
					$_date_ = ($_day_name_full.', '.$_day.' '.$_month_name_full.' '.$date->format("Y").' at '.$date->format("H:i"));
				}
				return $_date_;
				break;
		}
		return;
	}

}