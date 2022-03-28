<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class CategoriesController extends Controller
{
    public function index(Request $request){
        $categories = Category::paginate($request['per_page'] ?? 5);

        return CategoryResource::collection($categories);    
    }

    // public function create(){
    //     $data = Category::all();
        
    //     // tạo hàm đệ quy cho danh mục sản phẩm
    //     $recusive = new Recusive;
    //     $htmlSelect = $recusive->recusive($data, '');
        
    //     return view('categories.create', [
    //         'htmlSelect' => $htmlSelect,
    //     ]);
    // }

    public function store(StoreCategoryRequest $request){
        $input = $request->all();
        $input['slug'] = SlugService::createSlug(Category::class, 'slug', $input['name']);
        $category = Category::create($input);

        return new CategoryResource($category);
    }

    // public function edit($id){
    //     $category = Category::find($id);
       
    //     // tạo hàm đệ quy cho danh mục sản phẩm
    //     $data = Category::all();
    //     $recusive = new Recusive;
    //     $parentData = $category->parent_id;
    //     $htmlSelect = $recusive->recusive($data,  $parentData);
    
    //     return view('categories.edit', [
    //         'htmlSelect' => $htmlSelect,
    //         'category' => $category,
    //     ]);
    // }

    public function update(UpdateCategoryRequest $request, $id){
        $category = Category::findOrFail($id);
        $input = $request->all();
        $input['slug'] = SlugService::createSlug(Category::class, 'slug', $request->name);  
        $category->update($input);

        return new CategoryResource($category);
    
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return new CategoryResource($category);
    }
  
    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json('ok');
    }
}