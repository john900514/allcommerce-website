<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CapeAndBay\AllCommerce\Facades\ServiceDesk;

class MerchMgntController extends Controller
{
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Show the Merch Mgnt dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $inventory = ServiceDesk::get('inventory');

        if($inventory)
        {
            $merch = $inventory->getItemsArray();

            $testload = [
                'title' => ['=', '24-pk of Crayons']
            ];
            $item = $inventory->getItemArray($testload);
        }
        else
        {
            $merch = [];
        }

        $args = [
            'params' => [
                'name' => 'Merchandise',
                'button_panel_buttons' => [
                    [
                        'name' => 'Add Merch',
                        'url' => '/access/merch/add'
                    ]
                ],
                'action_panel_buttons' => [
                    [
                        'name' => 'Import'
                    ],
                    [
                        'name' => 'Export'
                    ]
                ],
                'grid_filters' => [
                    [
                        'name' => 'All',
                        'id' => 'searchAll'
                    ]
                ],
                'merch' => $merch
            ],

        ];

        return view('merch.index', $args);
    }
}
