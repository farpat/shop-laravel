@extends('_layouts.app')

@section('title', __('Your billings'))

@section('description', __('Your billings'))

@section('content')
    <div class="container">
        <h1>{{ __('Your :count billings', ['count' => count($billings)]) }}</h1>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Total amount including taxes') }}</th>
                <th>{{ __('Number of items') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php /** @var App\Models\Cart $billing */ ?>
            @foreach($billings as $billing)
                <tr>
                    <td>{{ $billing->number }}</td>
                    <td>{{ $billing->formatted_total_amount_including_taxes }}</td>
                    <td> {{ $billing->items_count }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('cart.export_billing', ['billing' => $billing->number]) }}" class="btn btn-light"><i class="fas fa-file-pdf fa-1x"></i></a>
                            <a href="#" class="btn btn-light"><i class="far fa-eye fa-1x"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
