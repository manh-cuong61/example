<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {           
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'permissions_count' => $this->permissions_count,
            'permission' => PermissionResource::collection($this->whenLoaded('permissions'), function (){
                return $this->permissions->name;
            }),
            'users' => UserResource::collection($this->whenLoaded('users')),
            
        ];
    }
}
