<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Book Catalogue</title>        
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    @yield('stylesheets')
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="text-align: center;">
      <a class="navbar-brand" href="{{ route('home') }}">Book Catalogue</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">                      
            <li class="nav-item">
              <a class="nav-link" href="{{ route('books.index') }}">Books</a>
            </li>            
        </ul>    
      </div>
    </nav>    
    <br>
    <div class="container-fluid">
      @yield('content')
    </div>    
    <br>
    <nav class="navbar navbar-dark bg-primary">
      <ul class="navbar-nav mx-auto">                      
        <li class="nav-item">
          <a class="nav-link" href="https://github.com/wahyaumau">github/wahyaumau</a>
        </li>            
      </ul>    
    </nav>
    <!-- <div style="text-align: center; color: #fff; margin: auto;" id="background"> -->
      
    <!-- </div> -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    @yield('javascripts')
  </body>
</html>