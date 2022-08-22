<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Link::orderBy('created_at', 'DESC')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreLinkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLinkRequest $request)
    {
        $validated = $request->validated();
        $link = Link::create($validated);
        return response()->json($link, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Link  $link
     * @return \Illuminate\Http\Response
     */
    public function show(Link $link)
    {
        return response()->json($link, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateLinkRequest  $request
     * @param  Link  $link
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLinkRequest $request, Link $link)
    {
        $validated = $request->validated();
        $link->update($validated);
        return response()->json($link, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Link  $link
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link)
    {
        $link->delete();
        return response()->json(null, 204);
    }
}
