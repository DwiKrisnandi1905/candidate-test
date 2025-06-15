<div class="text-sm text-black">
    @foreach ($items as $item)
        @if (!$loop->last)
            <a href="{{ $item['url'] }}" class="hover:underline">{{ $item['label'] }}</a> /
        @else
            <span class="font-semibold">{{ $item['label'] }}</span>
        @endif
    @endforeach
</div>
