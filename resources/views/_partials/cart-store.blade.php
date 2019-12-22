<script>
    window._CartStore = {
        state: {
            cartItems: @json($cartItems, JSON_FORCE_OBJECT),
            cartItemsLength: {{ count($cartItems) }},
        },
        data:  {
            currencyCode:    '{{ $currencyCode }}',
            purchaseUrl: '{{ route('cart.purchase') }}',
        }
    };
</script>