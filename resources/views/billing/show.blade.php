@extends('_layouts.pdf')

@section('title', $billing->number)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col">
                <p>
                    <strong>{{ config('app.name') }}</strong><br>
                    {{ parameter('billing', 'address')->line1 }}<br>
                    @if(parameter('billing', 'address')->line2){{ parameter('billing', 'address')->line2 }}<br>@endif
                    {{ parameter('billing', 'address')->postal_code }} {{ parameter('billing', 'address')->city }}
                    {{ parameter('billing', 'address')->country }}<br>
                    <br>
                </p>
                <p>
                    <i class="fas fa-phone"></i> 06 52 38 41 52
                </p>
            </div>
        </div>

        <div class="row">
            <div class="offset-6 col">
                <strong>{{ $billing->user->name }}</strong><br>
                {{ $billing->address_line1 }}<br>
                @if($billing->address_line2){{ $billing->address_line2 }}<br>@endif
                {{ $billing->address_postal_code }} {{ $billing->address_city }}<br>
                {{ $billing->address_country }}
            </div>
        </div>

        <h1 class="h4 mt-3">{{ __('Billing') }} {{ $billing->number }}
            - {{ $billing->updated_at->format('d F Y') }}</h1>

        <table class="table table-bordered my-5">
            <thead>
            <tr>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Quantity') }}</th>
                <th>{{ __('Unit price excluding taxes') }}</th>
                <th>{{ __('Amount excluding taxes') }}</th>
            </tr>
            </thead>
            <tbody>
            <?php /** @var App\Models\CartItem $item */ ?>
            @foreach($billing->items as $item)
                <tr>
                    <td>{{ $item->product_reference->label }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">{{ $item->product_reference->formatted_unit_price_excluding_taxes }}</td>
                    <td class="text-right">{{ $item->formatted_amount_excluding_taxes }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="header-cart-total">
            <tr>
                <td colspan="2">{{ __('Total amount excluding taxes') }} :</td>
                <td colspan="2">{{ $billing->formatted_total_amount_excluding_taxes }}</td>
            </tr>
            <tr class="header-cart-total-vat">
                <td class="text-right" colspan="2">{{ __('Including taxes') }} :</td>
                <td colspan="2">{{ $billing->formatted_including_taxes }}</td>
            </tr>
            <tr style="font-size: 1.5rem;">
                <td class="text-right" colspan="2">{{ __('Total amount including taxes') }} :</td>
                <td colspan="2">{{ $billing->formatted_total_amount_including_taxes }}</td>
            </tr>
            </tfoot>
        </table>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit <br><br>

            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores exercitationem iusto quis repudiandae
            sapiente? Ab aliquid consequatur cupiditate deleniti, eum illum laborum maxime, mollitia, nam nobis non
            officiis saepe voluptatem.
        </p>
    </div>
@endsection