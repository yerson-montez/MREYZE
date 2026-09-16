@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mreyze-error text-sm text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
