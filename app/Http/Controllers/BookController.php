<?php

namespace App\Http\Controllers;

use App\Book;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

class BookController extends Controller
{
    function createBlobClient() {
        $connectionString = "DefaultEndpointsProtocol=https;AccountName=".env('AZURE_STORAGE_ACCOUNT_NAME').";AccountKey=".env('AZURE_STORAGE_ACCOUNT_KEY');        
        return BlobRestProxy::createBlobService($connectionString);        
    }

    function createContainer($containerName){
        $blobClient = $this->createBlobClient();
        // Create container options object.
        $createContainerOptions = new CreateContainerOptions();

        // Set public access policy.
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

        // Set container metadata.
        $createContainerOptions->addMetaData("key1", "value1");        

        // Create container.            
        $blobClient->createContainer($containerName, $createContainerOptions);            
    }

    function generateUniqueFileName($file){
        $fileName = $file->getClientOriginalName();
        $fileNameWOExtension = pathinfo($fileName, PATHINFO_FILENAME);
        return $fileNameWOExtension.'-'.now().'.'.$file->getClientOriginalExtension();
    }
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
            $book->cover_url = env('AZURE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileUniqueName;
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
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',            
            'author' => 'required|string',
            'release_year' => 'required|integer',
            'cover' => 'mimes:jpeg,bmp,png'
        ]);

        try {                                            
            if($request->hasFile('cover')){                
                $blobClient = $this->createBlobClient();

                $containerName = env('AZURE_STORAGE_CONTAINER');
                $uploadedFile = $request->cover;
                $fileContent = fopen($uploadedFile, "r");                
                $fileUniqueName = $this->generateUniqueFileName($uploadedFile);
                
                // Delete old blob
                $oldBlobName = str_replace(env('AZURE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/', '', $book->cover_url);
                $blobClient->deleteBlob($containerName, $oldBlobName);

                // Upload updated blob
                $blobClient->createBlockBlob($containerName, $fileUniqueName, $fileContent);
                $book->cover_url = env('AZURE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/'.$fileUniqueName;
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
    public function destroy(Book $book)
    {
        try{                        
            $blobClient = $this->createBlobClient();
            $oldBlobName = str_replace(env('AZURE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/', '', $book->cover_url);
            $blobClient->deleteBlob(env('AZURE_STORAGE_CONTAINER'), $oldBlobName);
            $book->delete();
            return redirect()->route('books.index')->with('success', 'Book deleted');
        }catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());            
        }
    }

    public function review(Request $request, $bookId)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'review' => 'required|string',                        
            'rating' => 'required|integer|gt:0|lte:10',            
        ]);

        try{            
            $book = Book::where('id', $bookId)->firstOrFail();
            $review = new Review;
            $review->fill($request->all());
            $review->book_id = $book->id;
            $review->save();
            return redirect()->route('books.show', $book)->with('success', 'Review added');
        }catch (\Exception $e) {
            return redirect()->route('books.show')->with('error', $e->getMessage());
        }
        

    }
}
