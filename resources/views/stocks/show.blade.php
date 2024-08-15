<!DOCTYPE html>
<html>
<head>
    <title>Stock Price Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="container">
    <h1>Latest Stock Price</h1>
    <x-stock-price :stock="$stock" :stockPrice="$latestPrice" :percentChange="$percentChange" />
</div>
</body>
</html>
