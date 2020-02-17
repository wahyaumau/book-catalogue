@extends('template')

@section('content')

@if (\Session::has('success'))
	<div class="row justify-content-center">
	    <div class="alert alert-success col-md-11">
	        <p>{{ \Session::get('success') }}</p>
	    </div>
	</div>
@elseif (\Session::has('error'))
	<div class="row justify-content-center">
	    <div class="alert alert-danger col-md-11">
	        <p>{{ \Session::get('error') }}</p>
	    </div>
	</div>
@endif
<a href="{{route('books.create')}}" class="btn btn-primary">Add book</a>

<br>
<div class="row">
    <div class="col-md-12">
        <form action="{{route('books.search')}}" method="post" class="form-inline">
            @csrf
            <div class="form-group">
                <label>Query</label>
                <input type="text" name="search" class="form-control mx-sm-3">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>
    <br>
    @foreach($listBook as $key => $book)
    <div class="col-md-4">
        <div class="card mb-3">
        <img src="{{$book->cover_url}}" height="300px" width="300px" class="mx-auto rounded d-block">
            <div class="card-body">
                <h5 class="card-title">{{ $book->title }}</h5>                
                <p class="card-text"><small class="text-muted">Author : {{ $book->author }} Year : {{ $book->release_year }}</small></p>
                <div class="row mx-auto">
                    <a href="{{route('books.show', $book->id)}}" class="btn btn-primary">Detail</a>
                    <a href="{{route('books.edit', $book->id)}}" class="btn btn-warning">Edit</a>                
                    <form action="{{route('books.destroy', $book->id)}}" method="post">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Delete</button>                    
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection