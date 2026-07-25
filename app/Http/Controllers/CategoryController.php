<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query()->where('user_id', $request->user()->id)
        ->when(
            $request->input('search'), function ($query, $search) {
                $query->where('name', 'like' , '%'.$search.'%');
            }
        )
        ->when(
            $request->input('type'), function ($query, $type) {
                $query->where('type', $type);
            }
        )
        ->paginate(10)->withQueryString();
        return view('pages.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $requestData = $request->validated();
        $requestData['user_id'] = $request->user()->id;
        Category::create($requestData);
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,string $id)
    {
        $category = Category::findOrFail($id);
        if($category->user_id != $request->user()->id){
            return abort(404);
        }
        return view('pages.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        $requestData = $request->validated();
        $category = Category::findOrFail($id);
        if($category->user_id != $request->user()->id){
            return abort(404);
        }
        $category->update($requestData);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        $category = Category::findOrFail($id);
        if($category->user_id != $request->user()->id){
            return abort(404);
        }
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
