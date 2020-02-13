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
    @foreach($listBook as $key => $book)
    <div class="col-md-4">
        <div class="card mb-3">
        <img src="https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fstatic.onecms.io%2Fwp-content%2Fuploads%2Fsites%2F6%2F2016%2F09%2Fhpsorcstone.jpg" height="300px" width="300px" class="mx-auto rounded d-block">
            <div class="card-body">
                <h5 class="card-title">{{ $book->title }}</h5>                
                <p class="card-text"><small class="text-muted">Author : {{ $book->author }} Year : {{ $book->release_year }}</small></p>
                <div class="row mx-auto">
                    <a href="{{route('books.show', $book)}}" class="btn btn-primary">Detail</a>
                    <a href="{{route('books.edit', $book)}}" class="btn btn-warning">Edit</a>                
                    <form action="{{route('books.destroy', $book)}}" method="post">
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