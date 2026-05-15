<nav class="navbar navbar-expand-lg navbar-dark bg-primary"> 
    <div class="container"> 
        <a class="navbar-brand" href="/"> 
            <i class="fas fa-code me-2"></i> 
            Belajar Controller 
        </a> 
        <button class="navbar-toggler" type="button" data-bstoggle="collapse" data-bs-target="#navbarNav"> 
            <span class="navbar-toggler-icon"></span> 
        </button> 
        <div class="collapse navbar-collapse" id="navbarNav"> 
<ul class="navbar-nav ms-auto"> 
<li class="nav-item"> 
<a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"> 
<i class="fas fa-home me-1"></i>Home 
</a> 
</li> 
<li class="nav-item"> 
<a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}"> 
<i class="fas fa-info-circle me-1"></i>About 
</a> 
</li> 
<li class="nav-item"> 
<a class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}" 
href="{{ route('posts.index') }}"> 
<i class="fas fa-newspaper me-1"></i>Posts 
</a> 
</li> 
<li class="nav-item"> 
<a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}"> 
<i class="fas fa-envelope me-1"></i>Contact 
</a> 
</li> 
</ul> 
</div> 
</div> 
</nav>