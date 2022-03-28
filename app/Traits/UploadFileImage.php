<?php

namespace App\Traits;

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Storage;

trait UploadFileImage
{
    public function uploadImage($file){
            $fileName = uniqid() . '-' 
                    . str_replace(' ', '',$file->getClientOriginalName()); 
                    // . $file->extension();
            $filePath = $file->storeAs('public/images',  $fileName);
        
            return $dataImage = [
                'imageName' =>$fileName,
                'imagePath' => Storage::url($filePath),  
            ]; 
        
    }

}