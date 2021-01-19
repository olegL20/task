<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = new CategoryResource($category);
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'categories' => $categories
        ];
    }
}
