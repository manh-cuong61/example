<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    use \App\Traits\UploadFileImage;

    private $tags;
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page');
        $products = Product::latest()->paginate($perPage ?? 10);
        $products->load('images', 'category', 'tags');
        // $tags = $product->tags;
        return ProductResource::collection( $products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $credentials = $request->only('name', 'price', 'category_id', 'file_image', 'content', 'user_id', 'mutiple_file_image', 'tags');
            //upload main image for the product use UploadFileImage trait class
            if (!empty($credentials['file_image'])) {
                $dataImage = $this->uploadImage($credentials['file_image']);
                $credentials['feature_image_path'] = $dataImage['imagePath'];
                $credentials['feature_image_name'] = $dataImage['imageName'];
            }
            $product = Product::create([
                'name' => $credentials['name'],
                'price' => $credentials['price'],
                'category_id' => $credentials['category_id'],
                'feature_image_path' => $credentials['feature_image_path'],
                'feature_image_name' => $credentials['feature_image_name'],
                'user_id' => $credentials['user_id'],
                'content' => $credentials['content'],
            ]);

            //insert images to product_images table
            if ($request->hasFile('mutiple_file_image')) {
                foreach ($credentials['mutiple_file_image'] as $fileImage) {
                    $dataImage = $this->uploadImage($fileImage);
                    $product->images()->create([
                        'image_path' =>  $dataImage['imagePath'] ?? Null,
                        'image_name' => $dataImage['imageName'] ?? Null,
                    ]);
                }
            }

            //insert product's tags to tags table
            if (!empty($credentials['tags'])) {
                $tags = $credentials['tags'];
                foreach ($tags as $tag) {
                    $tag = $this->tag->firstOrCreate([
                        'name' => $tag,
                    ]);
                    $idTags[] = $tag->id;
                }
            }

            //create relationship to product_tags table 
            $product->tags()->sync($idTags);

            //load images from product_image table and load tags from tags table
            $product->load('tags', 'images');
            DB::commit();

            return new ProductResource($product);
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception->getMessage() . ' /Line : ' . $exception->getLine() . ' /File: ' . $exception->getFile());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product->load('category', 'images');
        
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $credentials = $request->only('name', 'price', 'category_id', 'file_image', 'content', 'user_id', 'mutiple_file_image', 'tags');

            //delete old main image and upload new main image for the product use UploadFileImage trait class
            if (!empty($credentials['file_image'])) {
                Storage::delete("public/images/$product->feature_image_name");
                $dataImage = $this->uploadImage($credentials['file_image']);
            }
            $product->update([
                'name' => $credentials['name'],
                'price' => $credentials['price'],
                'category_id' => $credentials['category_id'],
                'feature_image_path' =>  $dataImage['imagePath'] ?? Null,
                'feature_image_name' =>  $dataImage['imageName'] ?? Null,
                'content' => $credentials['content'],
                'user_id' => $credentials['user_id'],
            ]);

            if ($request->hasFile('mutiple_file_image')) {
                //delete the product's old images from product_images table 
                $product->images->map(function ($image) {
                    Storage::delete("public/images/$image->image_name");
                    $image->delete();
                });
                //insert new images to product_images table
                foreach ($credentials['mutiple_file_image'] as $fileImage) {
                    $dataImage = $this->uploadImage($fileImage);
                    $product->images()->create([
                        'image_path' =>  $dataImage['imagePath'] ?? Null,
                        'image_name' => $dataImage['imageName'] ?? Null,
                    ]);
                }
            }

            //insert product's tags to tags table
            if (!empty($credentials['tags'])) {
                $tags = $credentials['tags'];
                foreach ($tags as $tag) {
                    $tag = $this->tag->firstOrCreate([
                        'name' => $tag,
                    ]);
                    $idTags[] = $tag->id;
                }
            }

            //create relationship to product_tags table 
            $product->tags()->sync($idTags);

            //load images from product_image table and load tags from tags table
            $product->load('tags', 'images');
            DB::commit();
            
            return new ProductResource( $product);
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception->getMessage() . ' Line : ' . $exception->getLine());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            Storage::delete("public/images/$product->feature_image_name");
            //delete the product's images from product_images table
            if ($product->images->count() > 0) {
                $product->images->map(function ($image) {
                    Storage::delete("public/images/$image->image_name");
                    $image->delete();
                });
            }
            //delete relationship in product_tags table 
            $product->tags()->sync([]);
            $product->deslete();
            DB::commit();
            
            return response()->json([
                'data' => 'Ok',
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception->getMessage() .
                '/Line: ' . $exception->getLine() .
                '/File: ' . $exception->getFile());
        }
    }
}
