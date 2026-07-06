<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisHukuman extends Model
{
    protected $table = 'jenis_hukumans';

    protected $fillable = [
        'kategori',
        'rentang_poin',
    ];

    public static function categories(): array
    {
        return [
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi',
        ];
    }

    public static function ranges(): array
    {
        return [
            'rendah' => '0',
            'sedang' => '0.31 ',
            'tinggi' => '0.71',
        ];
    }

    public static function rangeForCategory(?string $kategori): string
    {
        return static::ranges()[$kategori] ?? '';
    }
}
