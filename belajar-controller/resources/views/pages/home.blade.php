@extends('layouts.master') 
@section('title', $title) 
@section('content') 
<div class="row"> 
    <div class="col-12 text-center mb-5"> 
        <h1 class="display-4">{{ $title }}</h1> 
        <p class="lead">{{ $subtitle }}</p> 
    </div> 
     
    <div class="col-md-8 mx-auto"> 
        <div class="card shadow"> 
            <div class="card-body"> 
                <h3>Fitur-fitur Laravel:</h3> 
                <ul class="list-group list-group-flush"> 
                    @foreach($features as $index => $feature) 
                        <li class="list-group-item d-flex align-itemscenter"> 
                            <span class="badge bg-primary rounded-pill me3">{{ $index + 1 }}</span> 
                            {{ $feature }} 
                            @if($loop->first) 
                                <span class="badge bg-success msauto">Featured</span> 
                            @endif 
                        </li> 
                    @endforeach 
                </ul> 
            </div> 
        </div> 
    </div> 
</div> 
@endsection