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

class PageController extends Controller
{
    const IMAGE_DIR = __DIR__ . '/../../../public/images/pages/'; // Where to cache the images.

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
        $path_parts = pathinfo($filename);
        $baseDirName = 'images/pages/';

        $height = 980;
        $width = 1920;

        // SHRINK TO FIT SECTION
        $filename = uniqid() . '.' . $path_parts['extension'];
        $output['images'][] = ['src'=> $baseDirName . $filename];
        \Image::make($output['photo'])
            ->heighten($height)
            ->widen($width, function($constraint){  $constraint->upsize(); })
            ->resizeCanvas($width, $height)
            ->save(self::IMAGE_DIR . $filename, 90);



        $page = new Page();

        # Set the parameters
        # Note how each parameter corresponds to a field in the table
        $page->name = $output['name'];
       
        $page->book_id = $output['book_id'];
        $page->user_id = Auth::id();
        $page->outline_url = $filename;

        # Invoke the Eloquent save() method
        # This will generate a new row in the `books` table, with the above data
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
        //
    }


    private function getAuthenticatedPage($page_id, $allowPublicBooks = false){
        $page = Page::with('book')->find($page_id);
        if( ($allowPublicBooks && $page->book->is_public) || ($page->user_id === Auth::id())){
            return $page;
        }
        return null;
    }
}
