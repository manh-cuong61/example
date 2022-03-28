<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSliderRequest;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    use \App\Traits\UploadFileImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('per_page');
        $sliders = Slider::paginate($limit ?? 5);
        return SliderResource::collection($sliders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSliderRequest $request)
    {
        $credentials = $request->only('name', 'description', 'file_image');

        $dataImage = $this->uploadImage($credentials['file_image']);

        $slider = Slider::create([
            'name' => $credentials['name'],
            'description' => $credentials['description'],
            'image_path' => $dataImage['imagePath'],
            'image_name' => $dataImage['imageName'],
        ]);

        return new SliderResource($slider);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slider = Slider::findOrFail($id);

        return new SliderResource($slider);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSliderRequest $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $credentials = $request->only('name', 'description', 'file_image');
        Storage::delete("public/images/$slider->image_name");
        $dataImage = $this->uploadImage($credentials['file_image']);

        $slider -> update([
            'name' => $credentials['name'],
            'description' => $credentials['description'],
            'image_path' => $dataImage['imagePath'],
            'image_name' => $dataImage['imageName'],
        ]);

        return new SliderResource($slider);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        Storage::delete("public/images/$slider->image_name");
        $slider->delete();

        return response()->json([
            'data' => 'delete success'
        ], 200);
    }
}
