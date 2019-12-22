<?php

namespace App\Services\Bank;


use App\Models\User;
use Stripe\Exception\InvalidRequestException;
use Stripe\{Customer, PaymentIntent, Stripe};

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

    public function charge (User $user, int $amountInCents): PaymentIntent
    {
        $customerId = $this->getCustomerId($user);

        $card = Customer::createSource($customerId, [
            'source' => $this->stripeToken
        ]);

        $paymentIntent = PaymentIntent::create([
            'customer' => $customerId,
            'amount'   => $amountInCents,
            'currency' => $this->currency,
            'source'   => $card->id
        ]);

        return $paymentIntent->confirm();
    }

    /**
     * @param string $stripeToken
     *
     * @return StripeService
     */
    public function setToken (string $stripeToken): StripeService
    {
        $this->stripeToken = $stripeToken;
        return $this;
    }

    private function getCustomerId (User $user): string
    {
        if (!$user->stripe_id) {
            $customer = $this->createCustomer($user->email);
            $user->update(['stripe_id' => $customer->id]);
        } else {
            try {
                Customer::retrieve($user->stripe_id);
            } catch (InvalidRequestException $e) {
                if ($e->getHttpStatus() === 404) {
                    $customer = $this->createCustomer($user->email);
                    $user->update(['stripe_id' => $customer->id]);
                } else {
                    throw $e;
                }
            }
        }

        return $user->stripe_id;
    }

    public function createCustomer (string $email): Customer
    {
        return Customer::create([
            'email'       => $email,
            'description' => 'Customer for ' . $email
        ]);
    }
}