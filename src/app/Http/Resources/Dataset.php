<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Dataset extends JsonResource
{
    public $allowedFields = ['id', 'category', 'firstname', 'lastname', 'email', 'gender', 'birthDate'];
    public $preserveKeys = false;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(\Illuminate\Http\Request $request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return [
            'id' => $this->whenHas('id'),
            'category' => $this->whenHas('category_id', $this->category?->name),
            'firstname' => $this->whenHas('firstname'),
            'lastname' => $this->whenHas('lastname'),
            'email' => $this->whenHas('email'),
            'gender' => $this->whenHas('gender_id', $this->gender?->name),
            'birthDate' => $this->whenHas('birthDate')
        ];
    }
}
