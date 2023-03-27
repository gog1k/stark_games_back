<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property boolean $active
 * @property-read RoomItem $items
 * @property-read array getItemsIdsAttribute
 */
class ItemTemplate extends Pivot
{
    protected $table = 'item_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'active',
        'name',
        'template',
    ];

    public function items(): HasManyThrough {
        return $this->hasManyThrough(
            RoomItem::class,
            RoomItemTemplate::class,
            'item_template_id',
            'id',
            'id',
            'room_item_id'
        );
    }

    public function getItemsIdsAttribute()
    {
        return $this->items->pluck('id');
    }
    //
}
