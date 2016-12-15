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
	const HEIGHT = 980;
	const WIDTH = 1920;

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

		// SHRINK TO FIT SECTION. Upload the image and resize it to the standard size.
		$filename = uniqid() . '.png';
		$output['images'][] = ['src'=> $baseDirName . $filename];
		\Image::make($output['photo'])
			->heighten(self::HEIGHT)
			->widen(self::WIDTH, function($constraint){  $constraint->upsize(); })
			->resizeCanvas(self::WIDTH, self::HEIGHT)
			->save(self::IMAGE_DIR . $filename, 90);

		$this->saveThumb(self::IMAGE_DIR . $filename, $filename);

		$page = new Page();
		$page->name = $output['name'];
		$page->book_id = $output['book_id'];
		$page->user_id = Auth::id();
		$page->outline_url = $filename;
		$page->save();

		return response()->json(['result'=> true]);
	}

	/**
	 * Validate the request for the store method.
	 *
	 * @param  $input - Associative array keyed by the name.
	 * @return $output - Associative array with clean values and errors if necessary.
	 */
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

		if(Auth::guest()){
			$output['errors']['user'] = 'Please log in.';
		}

		return $output;
	}

	/**
	 * Saves a coloring page. If the user does not own the original, a new page is created.
	 *
	 * @param  $request
	 * @return json response.
	 */
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

		// If we are not saving the outline, we need to save the 
		if(!$output['saveOutline']){
			$filename = $this->saveUploadedBase64Image($output['img']);
			$thumbFilename = $this->saveUploadedBase64Image($output['thumb']);
			$this->saveThumb(self::IMAGE_DIR . $thumbFilename, $filename);
			unlink(self::IMAGE_DIR . $thumbFilename);
		}	

		// If the user owns the page, and the user is not creating a new page, then just update the original page.
		if($originalPage->user_id === Auth::id() && $output['saveAs'] != 1){
			$page = $originalPage;
			$page->name = $output['name'];
			
			// Remove the old colored url if it exists.
			if($page->colored_url){
				if(file_exists(self::IMAGE_DIR . $page->colored_url)) { unlink(self::IMAGE_DIR . $page->colored_url); }
				if(file_exists(self::THUMB_DIR . $page->colored_url)) { unlink(self::THUMB_DIR . $page->colored_url); }
			}

			// We are updating the outline, keeping the same name.
			if($output['saveOutline'] ){
				// Remove the old outline_url if we are saving a new outline.
				if($page->outline_url){
					if(file_exists(self::IMAGE_DIR . $page->outline_url)) { unlink(self::IMAGE_DIR . $page->outline_url); }
					if(file_exists(self::THUMB_DIR . $page->outline_url)) { unlink(self::THUMB_DIR . $page->outline_url); }
				}
				$outlineFilename = $this->saveUploadedBase64Image($output['outline'], $page->outline_url); // Save using the same outline url.
				$this->saveThumb(self::IMAGE_DIR . $outlineFilename, $outlineFilename);
			}
		}else{
			// Either the user does not own the page, or they chose save as.
			$page = new Page();
			$page->name = $output['name'];
			$page->book_id = $originalPage->user_id === Auth::id() ? $output['book_id'] : null;
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

	/**
	 * Saves a base64 image.
	 *
	 * @param  string $img
	 * @return string filename.
	 */
	private function saveUploadedBase64Image($img, $filename = ''){
		$filename = $filename ? $filename : uniqid() . '.png';
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = self::IMAGE_DIR . $filename;
		$success = file_put_contents($file, $data);
		return $filename;
	}

	/**
	 * Saves a thumbnail.
	 *
	 * @param  string $path
	 * @param  string $filename
	 * @return string filename.
	 */
	private function saveThumb($path, $filename){
		\Image::make($path)
			->heighten(self::THUMB_HEIGHT)
			->widen(self::THUMB_WIDTH, function($constraint){  $constraint->upsize(); })
			->resizeCanvas(self::THUMB_WIDTH, self::THUMB_HEIGHT)
			->save(self::THUMB_DIR . $filename, 90);
		return $filename;
	}


	/**
	 * Validate the request for the saveColoringPage method.
	 *
	 * @param  $input - Associative array keyed by the name.
	 * @return $output - Associative array with clean values and errors if necessary.
	 */
	private function validateSaveColoringPage($input){
		if($input['book_id'] === 'null'){ // if the book_id comes as null, convert it to a real null.
			$input['book_id'] = null;
		}

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

	/**
	 * Moves the coloring page to a new book. Used with drag/drop.
	 *
	 * @param  $input - Associative array keyed by the name.
	 * @return json with result or errors.
	 */
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

	/**
	 * Validates the input for moveColoringPage.
	 *
	 * @param  $input - Associative array keyed by the name.
	 * @return $output - Associative array with clean values and errors if necessary.
	 */
	private function validateMoveColoringPage($input){
		if($input['book_id'] === 'null'){
			$input['book_id'] = null;
		}

		$defaults = [
			'id' => '',
			'book_id' => null,
		];

		$input = array_merge($defaults, $input);

		$output = []; // Output are variables that will be available to the html page.
		$output['errors'] = [];
		CustomValidator::validateField($input, $output, $defaults, 'id', 'required|numeric', null, true);        
		CustomValidator::validateField($input, $output, $defaults, 'book_id', 'numeric|nullable');

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

	/**
	 * Returns a user's page or public page.
	 *
	 * @param  int  $book_id
	 * @return bool $allowPublicBooks - Indicates if public books should be returned
	 */
	private function getAuthenticatedPage($page_id, $allowPublicBooks = false){
		$page = Page::with('book')->find($page_id);
		if( !$page_id || ($allowPublicBooks && $page->book &&  $page->book->is_public) || ($page->user_id === Auth::id())){
			return $page;
		}
		return null;
	}
}
