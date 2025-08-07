<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Welcome to the Waste Management System</h1>
        <p class="lead">This is a simple application to manage waste selling and recycling.</p>
        <a href="{{ route('sell-waste.create') }}" class="btn btn-primary">Sell Waste</a>
        <a href="{{ route('waste-types.index') }}" class="btn btn-secondary">View Waste Types</a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const weightInput = document.querySelector('input[name="weight_kg"]');
            const priceInput = document.querySelector('input[name="price_per_kg"]');
            const totalPriceInput = document.querySelector('input[name="total_price"]');

            function calculateTotalPrice() {
                const weight = parseFloat(weightInput.value) || 0;
                const pricePerKg = parseFloat(priceInput.value) || 0;
                totalPriceInput.value = (weight * pricePerKg).toFixed(2);
            }

            weightInput.addEventListener('input', calculateTotalPrice);
            priceInput.addEventListener('input', calculateTotalPrice);
        });
    </script>
</body>