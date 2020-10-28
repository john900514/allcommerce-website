<?php

namespace AllCommerce\Models\CheckoutFunnels;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class CheckoutFunnels extends Model
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
        'shop_id' => 'uuid',
        'shop_install_id' => 'uuid',
    ];

    public function getAllActiveFunnels($platform, $shop_uuid)
    {
        $results = [];

        $records = $this->whereShopPlatform($platform)
            ->whereShopInstallId($shop_uuid)
            ->whereActive(1)
            ->get();

        if(count($records) > 0)
        {
            $results = $records;
        }

        return $results;
    }

    public function insert(array $schema)
    {
        $results = false;

        $model = new $this();
        foreach($schema as $col => $val)
        {
            $model->$col = $val;
        }

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function getDefaultFunnelByShop($platform, $shop_uuid)
    {
        $results = false;

        $record = $this->whereShopPlatform($platform)
            ->whereShopInstallId($shop_uuid)
            ->whereDefault(1)
            ->whereActive(1)
            ->first();

        if(!is_null($record) > 0)
        {
            $results = $record;
        }

        return $results;
    }

    public function funnel_attributes()
    {
        return $this->hasMany('AllCommerce\CheckoutFunnelAttributes', 'funnel_uuid', 'id');
    }

    public function shop()
    {
        return $this->belongsTo('AllCommerce\Shops', 'shop_id','id');
    }

    public function shopify_install()
    {
        return $this->hasOne('AllCommerce\ShopifyInstalls', 'id', 'shop_install_id');
    }
}
