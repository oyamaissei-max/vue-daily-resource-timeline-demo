<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * リソース一覧を返す
     */
    public function index()
    {
        return Resource::orderBy('kind')->orderBy('name')->get();
    }
}