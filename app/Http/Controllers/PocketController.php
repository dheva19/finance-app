<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pocket\PocketStoreRequest;
use App\Http\Requests\Pocket\PocketUpdateRequest;
use App\Models\Pocket;
use Illuminate\Http\Request;

class PocketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pockets = Pocket::query()->when(
            $request->input('search'), function ($query, $search){
                $query->where('name', 'like', '%'.$search.'%');
            }
        )->where('user_id', $request->user()->id)->get();
        return view('pages.pockets.index', compact('pockets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pockets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PocketStoreRequest $request)
    {
        $requestData = $request->validated();
        $requestData['user_id'] = $request->user()->id;
        Pocket::create($requestData);
        return redirect()->route('pockets.index')->with('success', 'Kantong '. $requestData['name'] . ' berhasil ditambahkan!');
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
        $pocket = Pocket::findOrFail($id);
        if($pocket->user_id != $request->user()->id){
            return abort(404);
        }
        return view('pages.pockets.edit', compact('pocket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PocketUpdateRequest $request, string $id)
    {
        $requestData = $request->validated();
        $pocket = Pocket::findOrFail($id);
        if($pocket->user_id != $request->user()->id){
            return abort(404);
        }
        $pocket->update($requestData);
        return redirect()->route('pockets.index')->with('success', 'Kantong berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $pocket = Pocket::findOrFail($id);
        if($pocket->user_id != $request->user()->id){
            return abort(404);
        }
        $pocket->delete();
        return redirect()->route('pockets.index')->with('success', 'Kantong berhasil dihapus!');
    }
}
