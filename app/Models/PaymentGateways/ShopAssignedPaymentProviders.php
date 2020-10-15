<?php

namespace AnchorCMS\Models\PaymentGateways;

use Backpack\CRUD\CrudTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopAssignedPaymentProviders extends Model
{
    use CrudTrait, SoftDeletes, Uuid;

    protected $primaryKey  = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'id' => 'uuid',
        'shop_uuid' => 'uuid',
        'client_enabled_uuid' => 'uuid',
        'provider_uuid' => 'uuid',
        'merchant_uuid' => 'uuid',
        'client_uuid' => 'uuid',
    ];

    public function payment_provider()
    {
        return $this->hasOne('AnchorCMS\Models\PaymentGateways\PaymentProviders', 'id', 'provider_uuid')
            ->with('payment_type');
    }
}
