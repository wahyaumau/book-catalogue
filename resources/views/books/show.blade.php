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
    <div class="card mb-3">
        <img src="{{$book->cover_url}}" height="300px" width="300px" class="mx-auto rounded d-block">
        <div class="card-body">
            <h5 class="card-title">{{ $book->title }}</h5>                
            <p class="card-text"> {{$book->description}}</p>
            <p class="card-text"><small class="text-muted">Author : {{ $book->author }} Year : {{ $book->release_year }}</small></p>
        </div>        
    </div>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Reviews</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Add Review</a>            
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <br>
            @foreach($book->reviews as $review)
                <div class="row">
                    <div class="col-md-2">
                        <img src="https://image.ibb.co/jw55Ex/def_face.jpg" class="img img-rounded img-fluid"/>
                        <p class="text-secondary text-center">{{$review->updated_at}}</p>
                    </div>
                    <div class="col-md-10">
                        <p>
                            <a class="float-left" href="https://maniruzzaman-akash.blogspot.com/p/contact.html"><strong>{{$review->name}}</strong></a>
                            @for($i=0; $i<$review->rating; $i++)
                                <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                            @endfor        	                                    
                        </p>
                        <div class="clearfix"></div>
                        <p>{{$review->review}}</p>        	        
                    </div>
                </div>	     
            @endforeach  
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <br>
            <form method="POST" action="{{ route('books.review', $book) }}">
                @csrf                                
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>
                    <div class="col-md-8">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                        @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="body" class="col-md-2 col-form-label text-md-right">{{ __('Review') }}</label>
                    <div class="col-md-8">
                        <textarea id="review" rows="3" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="review" value="{{ old('review') }}" autofocus></textarea>
                        @if ($errors->has('review'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('review') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">{{ __('Rating') }}</label>
                    <div class="col-md-8">
                        <input id="rating" type="number" min="0" max="10" class="form-control{{ $errors->has('rating') ? ' is-invalid' : '' }}" name="rating" value="{{ old('rating') }}" required autofocus>
                        @if ($errors->has('rating'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('rating') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>                        
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-2">
                        <button type="submit" class="btn btn-primary">
                        {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>        
    </div>
	
	
    
@endsection