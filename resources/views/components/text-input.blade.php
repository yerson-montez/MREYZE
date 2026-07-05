@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-700 bg-gray-900 text-gray-200 focus:border-cyan-500 focus:ring-cyan-500/50 rounded-md shadow-sm placeholder-gray-500']) }}>
