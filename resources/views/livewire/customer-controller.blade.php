<div>
    <h1>Clientes de QuickBooks</h1>
    <ul>
        @foreach($customers as $customer)
            <li>{{ $customer['DisplayName'] }}</li>
        @endforeach
    </ul>
</div>
