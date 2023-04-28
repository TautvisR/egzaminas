<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $emfilter=new \stdClass();
        $emfilter->name="";
        $filter=$request->session()->get("book_filter", $emfilter);
        return inertia('Books/Index',[
            'can'=>[
                'edit'=>Gate::allows("edit"),
            ],
           "books"=>Book::filter($filter)->with('category')->get(),
            "filter"=>$filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Books/Create',[
            "categories"=>Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:3|max:32',
            'summary'=>'required|min:3|max:255',
            'isbn'=>'required|min:3|numeric',
            'page'=>'required|numeric',
            'category_id'=>'required'
        ],
        [
            'name'=>'Pavadinimas yra privalomas, ne ilgesnis negu 32 ir ne trumpesnis negu 32 simboliai',
            'summary'=>'Santrumpa yra privaloma, ne ilgesne negu 255 simboliai',
            'isbn'=>'Privalomas, tik skaitmenys',
            'page'=>'Privalomas, tik skaitmenys',
            'category_id'=>'Privalomas'
        ]);
        $book = Book::create($request->all());
        if ($request->file("picture")!=null){
            $request->file("picture")->store("/public/books");
            $book->picture=$request->file("picture")->hashName();
        }
        return to_route("books.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return inertia("Books/Edit",[
           "book"=>$book,
            "categories"=>Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        if ($request->file("picture")!=null){
            $request->file("picture")->store("/public/books");
            $book->picture=$request->file("picture")->hashName();
        }
        $book->fill($request->all());
        $book->save();
        return to_route("books.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return to_route("books.index");
    }
    public function filter(Request $request){
        $filter=new \stdClass();
        $filter->name=$request->name;
        $request->session()->put("book_filter", $filter);
        to_route("books.index");
    }
}
