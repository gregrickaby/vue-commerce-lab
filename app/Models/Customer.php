<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address',
        'city',
        'email',
        'name',
        'phone',
        'state',
        'zip',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['lifetime_orders', 'lifetime_revenue'];

    /**
     * Define the relationship with Order.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Accessor for the total number of lifetime orders for the customer.
     */
    public function getLifetimeOrdersAttribute(): int
    {
        $cacheKey = "customer_{$this->id}_lifetime_orders";

        return Cache::remember($cacheKey, now()->addHours(24), function () {
            return $this->orders->count();
        });
    }

    /**
     * Accessor for the total lifetime revenue generated by the customer.
     */
    public function getLifetimeRevenueAttribute(): int
    {
        $cacheKey = "customer_{$this->id}_lifetime_revenue";

        return Cache::remember($cacheKey, now()->addHours(24), function () {
            return $this->orders->sum('total_revenue');
        });
    }

    /**
     * Scope to get the top customers by total revenue in the last specified number of days.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeBestCustomers($query, int $days = 7, int $limit = 3): Collection
    {
        return $query->with([
            'orders' => function ($query) use ($days) {
                $query->where('created_at', '>=', Carbon::now()->subDays($days));
            },
        ])
            ->get()
            ->each(function ($customer) {
                $customer->total_revenue = $customer->lifetime_revenue;
            })
            ->sortByDesc('total_revenue')
            ->take($limit)
            ->values();
    }
}
