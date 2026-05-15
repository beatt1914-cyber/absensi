@extends('layouts.master') 
 
@section('title', 'About') 
 
@section('content') 
<div class="row"> 
    <div class="col-12"> 
        <h1 class="mb-4">Tentang {{ $company }}</h1> 
         
        <div class="card shadow"> 
            <div class="card-body"> 
                <p><strong>Berdiri sejak:</strong> {{ $established }}</p> 
                 
                <h4 class="mt-4">Visi:</h4> 
                <p class="lead">{{ $vision }}</p> 
                 
                <h4 class="mt-4">Misi:</h4> 
                <ul class="list-group"> 
                    @foreach($mission as $item) 
                        <li class="list-group-item"> 
                            <i class="fas fa-check-circle text-success me2"></i> 
                            {{ $item }} 
                        </li> 
                    @endforeach 
                </ul> 
            </div> 
        </div> 
    </div> 
</div> 
@endsection