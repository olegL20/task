<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    /**
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function filter(array $filters)
    {
        $query = Product::query();
        if (key_exists('from_price', $filters)) {
            $query = $query->where('price', '>=', $filters['from_price']);
        }
        if (key_exists('to_price', $filters)) {
            $query = $query->where('price', '<=', $filters['to_price']);
        }
        if (key_exists('title', $filters)) {
            $query = $query->where('title', $filters['title']);
        }
        if (key_exists('public', $filters)) {
            $query = $query->where('public', $filters['public']);
        }
        if (key_exists('category_name', $filters)) {
            $query = $query->whereHas('categories', function (Builder $query) use ($filters) {
                $query->where('title', $filters['category_name']);
            });
        }

        return $query->get();
    }
}
