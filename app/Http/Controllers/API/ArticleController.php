<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $articles = auth()->user()->articles()
                ->where('title', 'like', '%' . request('keyword') . '%')
                ->paginate(10);

    return response()->json([
        'message'   => 'success',
        'data'      => ArticleResource::collection($articles),
    ]);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cover      = $request->file('cover');
        $fileName   = time().'_'.$cover->getClientOriginalName();
        $filePath   = $cover->storeAs('images/articles', $fileName, 'public'); 

        $article = auth()->user()->articles()->create([
            'cover'     => $filePath,
            'title'     => $request->title,
            'slug'      => \Str::slug($request->title),
            'content'   => $request->content,
        ]);

        return response()->json([
            'message'   => 'success',
            'data'      => new ArticleResource($article),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = auth()->user()->articles()->find($id);

        if (!$article) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Article not found',
            ]);
        }

        return response()->json([
            'message'   => 'success',
            'data'      => new ArticleResource($article),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = auth()->user()->articles()->find($id);

        if (!$article) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Article not found',
            ]);
        }

        $cover      = $request->file('cover');
        if ($cover) {
            \Storage::delete('public/'.$article->cover);
            $fileName   = time().'_'.$cover->getClientOriginalName();
            $filePath   = $cover->storeAs('images/articles', $fileName, 'public');
        } else {
            $filePath   = $article->cover;
        }
        

        $article->update([
            'cover'     => $filePath,
            'title'     => $request->title ?? $article->title,
            'slug'      => $request->title ? \Str::slug($request->title) : $article->slug,
            'content'   => $request->content ?? $article->content,
        ]);

    return response()->json([
        'message'   => 'success',
        'data'      => new ArticleResource($article),
    ]);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = auth()->user()->articles()->find($id);

        if (!$article) {
            return response()->json([
                'message'   => 'error',
                'data'      => 'Article not found',
            ]);
        }

        \Storage::delete('public/'.$article->cover);
        $article->delete();

        return response()->json([
            'message'   => 'success',
        ]);
    }
}
