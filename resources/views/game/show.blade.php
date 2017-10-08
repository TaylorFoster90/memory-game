@extends('layouts.default')

@section('page-content')

<section class="play-area">
  <div class="container">
    <div class="row">

      <div class="col-12">
        <h1 class="text-center">Play New Game</h1>
      </div>

      <div class="col-12">

        <div id="gameboard" class="clearfix">

          <div class="gameboard-block blue"><a href="#" class="inner"></a></div>
          <div class="gameboard-block green"><a href="#" class="inner"></a></div>
          <div class="gameboard-block yellow"><a href="#" class="inner"></a></div>
          <div class="gameboard-block red"><a href="#" class="inner"></a></div>

        </div>


      </div>

    </div>
  </div>
</section>

@endsection
