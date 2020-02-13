<?php

namespace App\Http\Controllers;

use App\Book;
use App\Review;
use Illuminate\Http\Request;

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
            // 'image_name' => 'required|date|after:awal_pendaftaran',            
        ]);

        try{                  
            Book::create($request->all());
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
            // 'image_name' => 'required|date|after:awal_pendaftaran',            
        ]);

        try{            
            $book->update($request->all());
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
            $book->delete();
            return redirect()->route('books.index')->with('success', 'Book deleted');
        }catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());            
        }
    }

    public function review(Request $request, Book $book)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'review' => 'required|string',                        
            'rating' => 'required|integer|gt:0|lte:10',            
        ]);

        try{            
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
