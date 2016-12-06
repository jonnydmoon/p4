<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Book;
use App\Page;
use App\CustomValidator;

class GeneralController extends Controller
{
	public function root()
	{
		$books = Book::where('user_id', '=', 1)->get();

		return view('root')->with(['books' => $books]);
	}

	public function publicBook($book_id)
	{
		$book = Book::where('user_id', '=', 1)->where('id', '=', $book_id)->first();
		$pages = Page::where('user_id', '=', 1)->where('book_id', '=', $book_id)->get();

		return view('book')->with(['pages' => $pages, 'book'=> $book]);
	}

	public function myAccount(){
		$user = Auth::user();

		if(!$user){
			return redirect()->route('root');
		}

		return view('auth.my-account')->with(['name' => $user->name, 'email' => $user->email, 'update_password' => '']);
	}
	

	public function saveMyAccount(Request $request){

		$output = $this->validateSaveMyAccount($request->all());

		if(count($output['errors'])){
        	return view('auth.my-account')->with($output);
        }

		$user = Auth::user();
		$user->name = $output['name'];
		$user->email = $output['email'];

		if($output['password']){
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

        if($input['update_password'] === 'on'){
        	CustomValidator::validateField($input, $output, $defaults, 'password', 'required|string|max:255');        
        	CustomValidator::validateField($input, $output, $defaults, 'password_confirmation', 'required|string|max:255');

        	if($output['password'] !== $output['password_confirmation']){
        		$output['errors']['password'] = "Passwords do not match.";
        	}
        }
        
        return $output;
    }



}
