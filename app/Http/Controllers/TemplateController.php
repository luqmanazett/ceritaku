<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateCategory;

class TemplateController extends Controller
{
    public function index()
    {
        $categories = TemplateCategory::all();
        $templates = Template::with('category')->where('is_active', true)->get();
        
        return view('template-cover', compact('categories', 'templates'));
    }
}
