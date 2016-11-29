<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use App\Book;
use App\Page;

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

	public function save(Request $request)
	{

		// requires php5
		define('UPLOAD_DIR', __DIR__ . '/../../../public/images/colored-pages/');
		$img = $_POST['img'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = UPLOAD_DIR . uniqid() . '.png';
		$success = file_put_contents($file, $data);
		print $success ? $file : 'Unable to save the file.';
	}






}
