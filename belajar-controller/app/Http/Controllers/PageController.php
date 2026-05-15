<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class PageController extends Controller
{
    /**
     * Halaman Home dengan berbagai cara passing data
     */
    public function home()
    {
        // Data yang akan dikirim ke view
        $title = 'Selamat Datang di Website Kami';
        $subtitle = 'Belajar Laravel MVC';
        $features = [
            'Mudah dipelajari',
            'Struktur kode rapi',
            'Fitur lengkap',
            'Komunitas besar'
        ];

        // Cara 1: Compact
        return view('pages.home', compact(
            'title',
            'subtitle',
            'features'
        ));
    }

    /**
     * Halaman About dengan method with()
     */
    public function about()
    {
        return view('pages.about')
            ->with('company', 'PT. Teknologi Indonesia')
            ->with('established', '2024')
            ->with('vision', 'Menjadi perusahaan teknologi terdepan')
            ->with('mission', [
                'Mengembangkan solusi inovatif',
                'Memberikan layanan terbaik',
                'Membangun tim yang profesional'
            ]);
    }

    /**
     * Halaman Contact dengan array asosiatif
     */
    public function contact()
    {
        $data = [
            'address' => 'Jl. Sudirman No. 123, Jakarta',
            'phone' => '(021) 1234-5678',
            'email' => 'info@teknologi.com',
            'social_media' => [
                'facebook' => 'facebook.com/teknologi',
                'twitter' => 'twitter.com/teknologi',
                'instagram' => 'instagram.com/teknologi'
            ]
        ];

        return view('pages.contact', $data);
    }

    /**
     * Halaman Profile dengan parameter
     */
    public function profile($name, $role = 'user')
    {
        $user = [
            'name' => $name,
            'role' => $role,
            'joined' => '2024',
            'status' => 'active'
        ];

        return view('pages.profile', compact('user'));
    }
}