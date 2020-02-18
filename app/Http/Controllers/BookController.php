<?php

namespace App\Http\Controllers;

use App\Book;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use BenjaminHirsch\Azure\Search\Service;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $listBook = Book::all();
        return view('books.index', compact('listBook'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create');
    }    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',            
            'author' => 'required|string',
            'release_year' => 'required|integer',
            'cover' => 'required|mimes:jpeg,bmp,png'
        ]);
        
        try {
            $blobClient = $this->createBlobClient();
            $containerName = env('AZURE_STORAGE_CONTAINER');
                    
            $uploadedFile = $request->cover;
            $fileContent = fopen($uploadedFile, "r");
            $fileUniqueName = $this->generateUniqueFileName($uploadedFile);        
    
            // Upload blob
            $blobClient->createBlockBlob($containerName, $fileUniqueName, $fileContent);
                
            $book = new Book;
            $book->fill($request->all());
            $book->cover_url = env('AZURE_STORAGE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileUniqueName;
            $book->save();            
            return redirect()->route('books.index')->with('success', 'Book added');
        }catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',            
            'author' => 'required|string',
            'release_year' => 'required|integer',
            'cover' => 'mimes:jpeg,bmp,png'
        ]);

        try {     
            $book = Book::findOrFail($id);                                       
            if($request->hasFile('cover')){                
                $blobClient = $this->createBlobClient();

                $containerName = env('AZURE_STORAGE_CONTAINER');
                $uploadedFile = $request->cover;
                $fileContent = fopen($uploadedFile, "r");                
                $fileUniqueName = $this->generateUniqueFileName($uploadedFile);
                
                // Delete old blob
                $oldBlobName = str_replace(env('AZURE_STORAGE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/', '', $book->cover_url);
                $blobClient->deleteBlob($containerName, $oldBlobName);

                // Upload updated blob
                $blobClient->createBlockBlob($containerName, $fileUniqueName, $fileContent);
                $book->cover_url = env('AZURE_STORAGE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileUniqueName;
            }               
            $book->fill($request->all());
            
            $book->save();            
            return redirect()->route('books.index')->with('success', 'Book updated');
        }catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());            
        }
    }

    /** 
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $book = Book::findOrFail($id);
            $blobClient = $this->createBlobClient();
            $oldBlobName = str_replace(env('AZURE_STORAGE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/', '', $book->cover_url);
            $blobClient->deleteBlob(env('AZURE_STORAGE_CONTAINER'), $oldBlobName);
            $book->delete();
            return redirect()->route('books.index')->with('success', 'Book deleted');
        }catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());            
        }
    }

    public function review(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'review' => 'required|string',                        
            'rating' => 'required|integer|gt:0|lte:10',            
        ]);

        try{            
            $book = Book::findOrFail($id);
            $review = new Review;
            $review->fill($request->all());
            $review->book_id = $book->id;
            $review->save();
            return redirect()->route('books.show', $book)->with('success', 'Review added');
        }catch (\Exception $e) {
            return redirect()->route('books.show')->with('error', $e->getMessage());
        }
        

    }

    public function search(Request $request){
        $azureSearch = new Service(env('AZURE_SEARCH_BASE_URL'), env('AZURE_SEARCH_ADMIN_KEY'), env('AZURE_SEARCH_API_VERSION'));
        $result = $azureSearch->search(env('AZURE_SEARCH_INDEX'), $request->search);
        $listBook = json_decode(json_encode($result['value']));
        return view('books.index', compact('listBook'));
    }
}
