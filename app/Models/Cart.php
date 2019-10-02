<?php

namespace App\Models;

use App\Services\Bank\StringUtility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cart extends Model
{
    const ORDERING_STATUS = 'ORDERING';
    const ORDERED_STATUS = 'ORDERED';
    const DELIVRED_STATUS = 'DELIVRED';

    protected $fillable = [
        'items_count', 'total_amount_excluding_taxes', 'total_amount_including_taxes', 'status', 'comment', 'user_id',
        'address_id', 'number'
    ];

    protected $casts = [
        'total_amount_excluding_taxes' => 'float',
        'total_amount_including_taxes' => 'float',
    ];

    public function getFormattedTotalAmountIncludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->total_amount_including_taxes);
    }

    public function getFormattedTotalAmountExcludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->total_amount_excluding_taxes);
    }

    public function getIncludingTaxesAttribute ()
    {
        return $this->total_amount_including_taxes - $this->total_amount_excluding_taxes;
    }

    public function getFormattedIncludingTaxesAttribute ()
    {
        return StringUtility::getFormattedPrice($this->including_taxes);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'number';
    }

    public function address ()
    {
        return $this->belongsTo(Address::class);
    }

    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function items ()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getBillingPathAttribute() {
        return Storage::disk('public')->path('billings/' . $this->number . '.pdf');
    }
}
