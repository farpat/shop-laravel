@extends('_layouts.pdf')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col">
                <img src="https://mondrian.mashable.com/uploads%252Fcard%252Fimage%252F918220%252F316bce31-4c38-4f3b-b743-a17406175286.png%252F950x534__filters%253Aquality%252880%2529.png"
                     width="150" alt="logo">
                <p>
                    <strong>{{ $billing->user->name }}</strong><br>
                    Résidence la Bienvenue<br>
                    4 boulevard Henri Barnier<br>
                    13015 Marseille<br>
                    France
                </p>
                <p>
                    <i class="fas fa-phone"></i> 06 52 38 41 52
                </p>
            </div>
        </div>

        <div class="row">
            <div class="offset-6 col">
                <strong>Piscine et Jardin</strong><br>
                25 Mail Haroun Tazieff<br>
                44000 Nantes<br>
                France
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