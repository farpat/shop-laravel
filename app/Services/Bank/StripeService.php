<?php

namespace App\Services\Bank;


use App\Models\User;
use Stripe\{Customer, Exception\CardException, Exception\InvalidRequestException, PaymentIntent, Stripe};
use Exception;
use Illuminate\Session\Store as Session;

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
    /**
     * @var string
     */
    private $stripeToken;

    public function __construct (string $key, string $secret, string $currency)
    {
        $this->key = $key;
        $this->secret = $secret;

        Stripe::setApiKey($secret);
        $this->currency = $currency;
    }

    public function charge (User $user, int $amountInCents, string $stripeToken): PaymentIntent
    {
        $this->stripeToken = $stripeToken;

        $stripeId = $this->getStripeId($user);

        $paymentIntent = PaymentIntent::create([
            'customer' => $stripeId,
            'amount'   => $amountInCents,
            'currency' => $this->currency,
            'source'   => $this->stripeToken
        ]);

        dd($paymentIntent);

        return $paymentIntent->confirm();
    }

    private function getStripeId (User $user): string
    {
        if (!$this->hasGoodStripeId($user->stripe_id)) {
            $this->generateStripeId($user);
        }

        return $user->stripe_id;
    }

    private function hasGoodStripeId (?string $stripeId): bool
    {
        if ($stripeId === null) return false;

        try {
            Customer::retrieve($stripeId);
        } catch (InvalidRequestException $e) {
            if ($e->getHttpStatus() === 404) {
                return false;
            }
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }

        return true;
    }

    private function generateStripeId (User $user): void
    {
        $customer = Customer::create([
            'email'       => $user->email,
            'description' => 'Customer for ' . $user->email,
            'source'      => $this->stripeToken
        ]);

        $user->update(['stripe_id' => $customer->id]);
    }
}