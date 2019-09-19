<?php

namespace App\Services\Bank;


use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $secret;
    /**
     * @var Stripe
     */
    private $stripe;
    /**
     * @var string
     */
    private $currency;

    public function __construct (string $key, string $secret, string $currency)
    {
        $this->key = $key;
        $this->secret = $secret;

        Stripe::setApiKey($secret);
        $this->currency = $currency;
    }

    public function charge (int $amountInCents): PaymentIntent
    {
        $paymentIntent = PaymentIntent::create([
            'amount'               => $amountInCents,
            'currency'             => $this->currency,
            'payment_method_types' => ['card']
        ]);

        return $paymentIntent->confirm([
            'payment_method' => 'pm_card_visa'
        ]);
    }
}