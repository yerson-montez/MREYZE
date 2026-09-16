@props(['active' => false])

<a {{ $attributes->class(['mreyze-header-link', 'is-active' => $active])->merge(['aria-current' => $active ? 'page' : null]) }}>{{ $slot }}</a>
