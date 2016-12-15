<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\CustomValidator;

class GeneralController extends Controller
{
	/**
	 * Shows the my account page for editing name, email, and password.
	 *
	 * @return view
	 */
	public function myAccount(){
		$user = Auth::user();
		return view('auth.my-account')->with(['name' => $user->name, 'email' => $user->email, 'update_password' => '']);
	}
	
	/**
	 * Saves the account information.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return view
	 */
	public function saveMyAccount(Request $request){

		$output = $this->validateSaveMyAccount($request->all());

		if(count($output['errors'])){
			return view('auth.my-account')->with($output);
		}

		$user = Auth::user();
		$user->name = $output['name'];
		$user->email = $output['email'];

		if($output['password']){ // If there is a password, store it, too.
			$user->password = bcrypt($output['password']);
		}

		try{
			$user->save();
		}catch(\Exception $exception){
			$output['errors']['email'] = "Email already in use.";
			return view('auth.my-account')->with($output);
		}

		return view('auth.saved');
	}

	/**
	 * Validate the request for the validateSaveMyAccount method.
	 *
	 * @param  $input - Associative array keyed by the name.
	 * @return $output - Associative array with clean values and errors if necessary.
	 */
	private function validateSaveMyAccount($input){
		$defaults = [
			'name' => '',
			'email' => '',
			'update_password' => '',
			'password' => '',
			'password_confirmation' => ''
		];

		$input = array_merge($defaults, $input);
		$output = []; // Output are variables that will be available to the html page.
		$output['errors'] = [];
		CustomValidator::validateField($input, $output, $defaults, 'name', 'required|string|max:255');        
		CustomValidator::validateField($input, $output, $defaults, 'email', 'required|string|max:255');
		CustomValidator::validateField($input, $output, $defaults, 'update_password', 'string');
		CustomValidator::validateField($input, $output, $defaults, 'password', 'string');

		if($input['update_password'] === 'on'){ // If set, we need to validate that passwords.
			CustomValidator::validateField($input, $output, $defaults, 'password', 'required|string|max:255');        
			CustomValidator::validateField($input, $output, $defaults, 'password_confirmation', 'required|string|max:255');

			if($output['password'] !== $output['password_confirmation']){
				$output['errors']['password'] = "Passwords do not match.";
			}
		}
		
		return $output;
	}
}
