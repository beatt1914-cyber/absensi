@extends('layouts.master') 
 
@section('title', 'Contact') 
 
@section('content') 
<div class="row"> 
    <div class="col-12"> 
        <h1 class="mb-4">Kontak Kami</h1> 
    </div> 
     
    <div class="col-md-6"> 
        <div class="card shadow"> 
            <div class="card-body"> 
                <h5 class="card-title mb-4">Informasi Kontak</h5> 
                 
                <table class="table table-borderless"> 
                    <tr> 
                        <td width="30"><i class="fas fa-map-marker-alt text-primary"></i></td> 
                        <td><strong>Alamat:</strong></td> 
                        <td>{{ $address }}</td> 
                    </tr> 
                    <tr> 
                        <td><i class="fas fa-phone text-primary"></i></td> 
                        <td><strong>Telepon:</strong></td> 
                        <td>{{ $phone }}</td> 
                    </tr> 
                    <tr> 
                        <td><i class="fas fa-envelope textprimary"></i></td> 
                        <td><strong>Email:</strong></td> 
                        <td>{{ $email }}</td> 
                    </tr> 
                </table> 
                 
                <h5 class="mt-4">Media Sosial:</h5> 
                <div class="d-flex gap-3"> 
                    <a href="https://{{ $social_media['facebook'] }}" class="btn btn-outline-primary btn-sm"> 
                        <i class="fab fa-facebook me-2"></i>Facebook 
                    </a> 
                    <a href="https://{{ $social_media['twitter'] }}" class="btn btn-outline-info btn-sm"> 
                        <i class="fab fa-twitter me-2"></i>Twitter 
                    </a> 
                    <a href="https://{{ $social_media['instagram'] }}" class="btn btn-outline-danger btn-sm"> 
                        <i class="fab fa-instagram me-2"></i>Instagram 
                    </a> 
                </div> 
            </div> 
        </div> 
    </div> 
     
<div class="col-md-6"> 
<div class="card shadow"> 
<div class="card-body"> 
<h5 class="card-title mb-4">Kirim Pesan</h5> 
<form> 
@csrf 
<div class="mb-3"> 
<label class="form-label">Nama</label> 
<input type="text" class="form-control"> 
</div> 
<div class="mb-3"> 
<label class="form-label">Email</label> 
<input type="email" class="form-control"> 
</div> 
<div class="mb-3"> 
<label class="form-label">Pesan</label> 
<textarea class="form-control" rows="4"></textarea> 
</div> 
<button type="submit" class="btn btn-primary"> 
<i class="fas fa-paper-plane me-2"></i>Kirim 
</button> 
</form> 
</div> 
</div> 
</div> 
</div> 
@endsection 