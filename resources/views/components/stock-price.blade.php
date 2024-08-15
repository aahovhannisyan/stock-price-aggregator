<div class="stock-price">
    <div class="price">
        <p>Open: {{ $stockPrice->open }}</p>
        <p>High: {{ $stockPrice->high }}</p>
        <p>Low: {{ $stockPrice->low }}</p>
        <p>Close: {{ $stockPrice->close }}</p>
        <p>Volume: {{ $stockPrice->volume }}</p>
    </div>
    <div class="change-indicator {{ $percentChange > 0 ? 'up' : 'down' }}">
        @if($percentChange > 0)
            <span class="indicator-icon">&#9650;</span> <!-- Up Arrow -->
        @elseif($percentChange < 0)
            <span class="indicator-icon">&#9660;</span> <!-- Down Arrow -->
        @else
            <span class="indicator-icon">&#8212;</span> <!-- Dash -->
        @endif
        ${{ $percentChange }} (Considering High price)
    </div>
</div>
