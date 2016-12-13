<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\CustomValidator;
use App\Page;
use App\Book;
use Session;

class PageController extends Controller
{
	const IMAGE_DIR = __DIR__ . '/../../../public/images/pages/'; // Where to cache the images.
	const THUMB_DIR = __DIR__ . '/../../../public/images/pages/thumbs/'; // Where to cache the images.
	const THUMB_WIDTH = 192;
	const THUMB_HEIGHT = 98;


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		

		return Response::json(); 
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$output = $this->validateStore($request->all());

		if(count($output['errors'])){
			return response()->json($output);
		}


		$filename = $output['photo']->getClientOriginalName();
		$baseDirName = 'images/pages/';

		$height = 980;
		$width = 1920;

		$thumbHeight = self::THUMB_HEIGHT;
		$thumbWidth = self::THUMB_WIDTH;

		// SHRINK TO FIT SECTION
		$filename = uniqid() . '.png';
		$output['images'][] = ['src'=> $baseDirName . $filename];
		\Image::make($output['photo'])
			->heighten($height)
			->widen($width, function($constraint){  $constraint->upsize(); })
			->resizeCanvas($width, $height)
			->save(self::IMAGE_DIR . $filename, 90);

		$this->saveThumb(self::IMAGE_DIR . $filename, $filename);

		$page = new Page();
		$page->name = $output['name'];
		$page->book_id = $output['book_id'];
		$page->user_id = Auth::id();
		$page->outline_url = $filename;
		$page->save();

		return response()->json(['a'=>'1']);
	}


	private function validateStore($input){
		$defaults = [
			'name' => '',
			'book_id' => '',
			'photo' => '',
		];

		$input = array_merge($defaults, $input);

		$output = []; // Output are variables that will be available to the html page.
		$output['errors'] = [];
		CustomValidator::validateField($input, $output, $defaults, 'name', 'required|string', 'Invalid value for "Name".');        
		CustomValidator::validateField($input, $output, $defaults, 'book_id', 'required|numeric', null, true);        
		CustomValidator::validateField($input, $output, $defaults, 'photo', "max:10000|mimes:jpg,jpeg,png|required", 'You must submit a file and it must be less than 10mb and be .jpg or .png');


		return $output;
	}


	public function saveColoringPage(Request $request)
	{
		$output = $this->validateSaveColoringPage($request->all());

		$originalPage = $this->getAuthenticatedPage($output['id'], true);

		if(is_null($originalPage)) {
			$output['errors']['id'] = 'Invalid page id.';
		}

		if(count($output['errors'])){
			return response()->json($output);
		}


		if(!$output['saveOutline']){
			$filename = $this->saveUploadedBase64Image($_POST['img']);
			$thumbFilename = $this->saveUploadedBase64Image($_POST['thumb']);
			$this->saveThumb(self::IMAGE_DIR . $thumbFilename, $filename);
			unlink(self::IMAGE_DIR . $thumbFilename);
		}	


		if($originalPage->user_id === Auth::id() && $output['saveAs'] != 1){
			$page = $originalPage;
			$page->name = $output['name'];
			

			if($page->colored_url){
				if(file_exists(self::IMAGE_DIR . $page->colored_url)) { unlink(self::IMAGE_DIR . $page->colored_url); }
				if(file_exists(self::THUMB_DIR . $page->colored_url)) { unlink(self::THUMB_DIR . $page->colored_url); }
			}

			if($output['saveOutline'] ){
				if($page->outline_url){
					if(file_exists(self::IMAGE_DIR . $page->outline_url)) { unlink(self::IMAGE_DIR . $page->outline_url); }
					if(file_exists(self::THUMB_DIR . $page->outline_url)) { unlink(self::THUMB_DIR . $page->outline_url); }
				}
				$outlineFilename = $this->saveUploadedBase64Image($_POST['outline'], $page->outline_url); // Save using the same outline url.
				$this->saveThumb(self::IMAGE_DIR . $outlineFilename, $outlineFilename);
			}

		}else{
			// SAVE AS: CREATE A NEW PAGE
			$page = new Page();
			$page->name = $output['name'];
			$page->book_id = $output['book_id'];
			$page->user_id = Auth::id();
			$page->outline_url = $originalPage->outline_url;

			if($output['saveOutline'] ){
				$outlineFilename = $this->saveUploadedBase64Image($_POST['outline']); // Save using the same outline url.
				$this->saveThumb(self::IMAGE_DIR . $outlineFilename, $outlineFilename);
				$page->outline_url = $outlineFilename;
			}

		}


		$page->colored_url = $output['saveOutline'] ? null : $filename;
		$page->save();

		return response()->json(['result'=>true, 'id'=>$page->id ]);
	}

	private function saveUploadedBase64Image($img, $filename = ''){
		$filename = $filename ? $filename : uniqid() . '.png';
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = self::IMAGE_DIR . $filename;
		$success = file_put_contents($file, $data);
		return $filename;
	}

	private function saveThumb($path, $filename){
		\Image::make($path)
			->heighten(self::THUMB_HEIGHT)
			->widen(self::THUMB_WIDTH, function($constraint){  $constraint->upsize(); })
			->resizeCanvas(self::THUMB_WIDTH, self::THUMB_HEIGHT)
			->save(self::THUMB_DIR . $filename, 90);
		return $filename;
	}


	private function validateSaveColoringPage($input){
		$defaults = [
			'name' => '',
			'id' => '',
			'book_id' => null,
			'img' => '',
			'thumb' => '',
			'outline' => '',
			'saveAs' => 1,
			'saveOutline' => 0,
		];

		$input = array_merge($defaults, $input);

		$output = []; // Output are variables that will be available to the html page.
		$output['errors'] = [];      
		CustomValidator::validateField($input, $output, $defaults, 'name', 'required|string', 'Invalid value for "Name".');        
		CustomValidator::validateField($input, $output, $defaults, 'id', 'required|numeric', null, true);        
		CustomValidator::validateField($input, $output, $defaults, 'book_id', 'numeric|nullable');        
		CustomValidator::validateField($input, $output, $defaults, 'img', "string|required", 'You must submit a file and it must be less than 10mb and be .jpg or .png');
		CustomValidator::validateField($input, $output, $defaults, 'thumb', "string|required", 'You must submit a file and it must be less than 10mb and be .jpg or .png');
		CustomValidator::validateField($input, $output, $defaults, 'outline', "string|required", 'You must submit a file and it must be less than 10mb and be .jpg or .png');
		CustomValidator::validateField($input, $output, $defaults, 'saveAs', 'required|numeric', 'Invalid value for "Save As".', true);  
		CustomValidator::validateField($input, $output, $defaults, 'saveOutline', 'required|numeric', 'Invalid value for "Save Outline".', true);  


		return $output;
	}


	public function moveColoringPage(Request $request)
	{
		$output = $this->validateMoveColoringPage($request->all());
		$page = $this->getAuthenticatedPage($output['id']);

		if(is_null($page)) {
			$output['errors']['id'] = 'Invalid page id.';
		}

		if(count($output['errors'])){
			return response()->json($output);
		}

		$page->book_id = $output['book_id'];
		$page->save();
		return response()->json(['result'=>true]);
	}


	private function validateMoveColoringPage($input){
		$defaults = [
			'id' => '',
			'book_id' => null,
		];

		$input = array_merge($defaults, $input);

		$output = []; // Output are variables that will be available to the html page.
		$output['errors'] = [];
		CustomValidator::validateField($input, $output, $defaults, 'id', 'required|numeric', null, true);        
		CustomValidator::validateField($input, $output, $defaults, 'book_id', 'numeric|nullable', null, true);
		return $output;
	}









	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$page = $this->getAuthenticatedPage($id, true);

		if(is_null($page)) {
			Session::flash('flash_message','Page not found');
			return redirect('/books');
		}

		return view('page')->with(['page' => $page ]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$page = $this->getAuthenticatedPage($id);

        if(is_null($page)) {
            return response()->json(['errors'=> ['You do not have permission to delete this page.'] ]);
        }

        $page->delete();
        return response()->json(['result'=> true ]);
	}


	private function getAuthenticatedPage($page_id, $allowPublicBooks = false){
		$page = Page::with('book')->find($page_id);
		if( !$page_id || ($allowPublicBooks && $page->book &&  $page->book->is_public) || ($page->user_id === Auth::id())){
			return $page;
		}
		return null;
	}
}
