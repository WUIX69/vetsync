<?php

namespace VetSync\Utils\Php;

class Helpers
{
    public static function productStatus($status)
    {
        $default = [
            'icon' => 'circle outline grey',
            'label' => 'unknown',
        ];

        $statuses = [
            'available' => [
                'icon' => 'check circle green',
                'label' => 'available',
            ],
            'unavailable' => [
                'icon' => 'times circle red',
                'label' => 'unavailable',
            ],
        ];

        return $statuses[$status] ?? $default;
    }

    public static function categoryName($category = [])
    {
        $default = [
            'icon' => 'folder outline grey',
            'label' => 'Uncategorized',
        ];

        $categories = [
            'icon' => $category['icon'],
            'label' => $category['name'],
        ];

        return $categories ?? $default;
    }
}