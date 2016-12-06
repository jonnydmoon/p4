<?php

namespace App;
use Illuminate\Support\Facades\Validator;

class CustomValidator{
	// This will populate the field in the output with either the input value, or, if not valid, the default value.
	static function validateField(&$input, &$output, $defaults, $key, $validator, $message = null, $forceToNumber = false){
		if(Validator::make($input, [$key => $validator])->fails()){
			$output['errors'][$key] = $message ? $message : "Invalid value for $key.";
			$input[$key] = $defaults[$key];
		}
		$output[$key] = $forceToNumber ? +$input[$key] : $input[$key];
	}
}