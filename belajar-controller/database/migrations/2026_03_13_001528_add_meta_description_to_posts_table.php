<?php 
use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\Schema; 
return new class extends Migration 
{ 
public function up(): void 
{ 
Schema::table('posts', function (Blueprint $table) { 
$table->string('meta_description')->nullable()->after('body'); 
$table->json('meta_keywords')->nullable() ->after('meta_description'); 
}); 
} 
public function down(): void 
{ 
Schema::table('posts', function (Blueprint $table) { 
$table->dropColumn(['meta_description', 'meta_keywords']); 
}); 
} 
};