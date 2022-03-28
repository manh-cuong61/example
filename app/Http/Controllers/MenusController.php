<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Components\Recusive;
use App\Http\Resources\MenuResource;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateCategoryRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menus = Menu::paginate($request['per_page'] ?? 5);
        return MenuResource::collection($menus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuRequest $request)
    {
        $input = $request->all();
        $input['slug'] = SlugService::createSlug(Menu::class, 'slug', $request->name);
        $menu = Menu::create($input);
        return new MenuResource($menu);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {    
        $input = $request->all();
        $input['slug'] = SlugService::createSlug(Menu::class, 'slug', $request->name); 
        $menu = Menu::findOrFail($id);
        $menu->update($input);
        return new MenuResource($menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        Menu::findOrFail($id)->delete();
        return response()->json('ok');
    }
}
