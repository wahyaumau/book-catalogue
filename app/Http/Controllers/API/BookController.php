<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Book;

class BookController extends Controller
{
    /**
     * @OA\Get(
     *      path="/books",
     *      operationId="getBookList",
     *      tags={"Books"},
     *      summary="Get list of book",
     *      description="Returns list of book",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",     
     *          @OA\JsonContent(ref="#/components/schemas/BookResourceResponse")
     *       ),
     *     )
     */
    public function index()
    {
        try{
            $listBook = Book::all();
            return $this->apiResponse(200, 'success', ['beasiswa' => $listBook]);
        }catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    /**
     * @OA\Post(
     *      path="/books",
     *      operationId="storeBook",
     *      tags={"Books"},
     *      summary="Store new book",
     *      description="Returns added book data",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",     
     *          @OA\JsonContent(ref="#/components/schemas/BookResponse")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * )
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
            return $this->apiResponse(200, 'success', ['book' => $book]);
        }catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    /**
     * @OA\Get(
     *      path="/books/{id}",
     *      operationId="getBookById",
     *      tags={"Books"},
     *      summary="Get book information",
     *      description="Returns book data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),     
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function show($id)
    {        
        try{            
            $beasiswa = Beasiswa::findOrFail($id);
            return $this->apiResponse(200, 'success', ['beasiswa' => $beasiswa]);
        }catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }                
    }

    /**
     * @OA\Patch(
     *      path="/books/{id}",
     *      operationId="updateBook",
     *      tags={"Books"},
     *      summary="Update existing book",
     *      description="Returns updated book data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",     
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
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
            return $this->apiResponse(200, 'success', ['book' => $book]);
        }catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    /**
     * @OA\Delete(
     *      path="/books/{id}",
     *      operationId="deleteBook",
     *      tags={"Books"},
     *      summary="Delete existing book",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Book id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),     
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id)
    {
        try{
            $book = Book::findOrFail($id);
            $blobClient = $this->createBlobClient();
            $oldBlobName = str_replace(env('AZURE_STORAGE_BASE_URL').'/'.env('AZURE_STORAGE_CONTAINER').'/', '', $book->cover_url);
            $blobClient->deleteBlob(env('AZURE_STORAGE_CONTAINER'), $oldBlobName);
            $book->delete();
            return $this->apiResponse(200, 'success', null);
        }catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }
}
