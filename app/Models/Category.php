<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $ancestors
 * @property-read int|null $ancestors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $descendants
 * @property-read int|null $descendants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static Builder<static>|Category leaf()
 * @mixin \Eloquent
 */
class Category extends Model {
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
    ];
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }
    public function ancestors(): BelongsToMany {
        return $this->belongsToMany(
            Category::class,
            'category_hierarchy',
            'descendant_id',
            'ancestor_id',
        )->withPivot('depth')->orderBy('category_hierarchy.depth');
    }

    public function descendants(): BelongsToMany {
        return $this->belongsToMany(
            Category::class,
            'category_hierarchy',
            'ancestor_id',
            'descendant_id',
        )->withPivot('depth')->orderBy('category_hierarchy.depth');
    }
    public function scopeLeaf(Builder $query) {
        return $query->whereNotIn(
            'id',
            DB::table('category_hierarchy')
                ->select(['ancestor_id'])
                ->where('depth', 1)
        );
    }
    public function parent(): ?Category {
        return $this->ancestors()->wherePivot('depth', 1)->first();
    }
    public function children(): BelongsToMany {
        return $this->descendants()->wherePivot('depth', 1);
    }
    public function isLeaf(): bool {
        return !$this->descendants()->wherePivot('depth', 1)->exists();
    }
    public function isRoot(): bool {
        return $this->parent() === null;
    }
    public function path() {
        return $this->ancestors()->orderBy('category_hierarchy.depth', 'desc')->get();
    }
    public function breadcrumbs() {
        return $this->path()->concat(collect([$this]));
    }
}
