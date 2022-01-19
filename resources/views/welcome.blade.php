<?php
  $disable = [
    'navbar',
    'header',
    'footer'
  ];
?>
@extends('layout.app')
@section('content')
  <header style="height: 100vh;">
    <div class="page-header section-height-100" style="height: calc(100% - 1rem);">
      <div class="oblique position-absolute top-0 h-100 d-md-block d-none">
        <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url(./assets/img/curved-images/curved11.jpg)"></div>
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6 col-md-7 d-flex justify-content-center flex-column">
            <h1 class="text-gradient text-primary">Praiser</h1>
            <h1 class="mb-4">Worship</h1>
            <p class="lead pe-5 me-5">The time is now for it be okay to be great. People in this world shun   people for being nice. </p>
            <div class="buttons">
              <button type="button" class="btn bg-gradient-primary mt-4">Get Started</button>
              <button type="button" class="btn text-primary shadow-none mt-4">Read more</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- -------- END HEADER 1 w/ text and image on right ------- -->
@endsection