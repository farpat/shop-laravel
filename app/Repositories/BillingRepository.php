<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Billing;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class BillingRepository
{
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    public function __construct (ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    private function makeBillingFromCart (Cart $cart, int $currentNumber): Billing
    {
        $addressArray = Address::findOrFail(
            $cart->address_id,
            ['text as address_text', 'line1 as address_line1', 'line2 as address_line2',
                'postal_code as address_postal_code', 'city as address_city', 'country as address_country',
                'latitude as address_latitude', 'longitude as address_longitude']
        )->toArray();

        $userArray = User::findOrFail(
            $cart->user_id,
            ['name as user_name', 'email as user_email']
        )->toArray();

        $cartArray = $cart->only(
            ['items_count', 'total_amount_excluding_taxes', 'total_amount_including_taxes', 'comment', 'user_id',
                'address_id']
        );

        $billingArray = array_merge(
            $cartArray,
            $addressArray,
            $userArray,
            ['number' => Billing::computeNextNumber(Carbon::now()->format('Y-m'), $currentNumber)]
        );

        return new Billing($billingArray);
    }

    private function rattachItemsToBilling (Cart $cart, Billing $billing)
    {
        OrderItem::query()
            ->where([
                'orderable_type' => Cart::class,
                'orderable_id'   => $cart->id
            ])
            ->update([
                'orderable_type' => Billing::class,
                'orderable_id'   => $billing->id
            ]);

        $cart->delete();
    }

    public function transformCartToBilling (Cart $cart): Billing
    {
        $billing = $this->makeBillingFromCart(
            $cart,
            $currentNumber = (int)$this->moduleRepository->getParameter('billing', 'next_number')->value
        );
        $billing->save();

        $this->rattachItemsToBilling($cart, $billing);

        $this->moduleRepository->updateParameter('billing', 'next_number', $currentNumber + 1);

        return $billing;
    }

    public function get (User $user): Collection
    {
        return Billing::query()
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();
    }
}