@props(['label', 'column', 'route', 'align' => 'left'])

@php
    // Default fallback params to prevent errors if not set
    $currentSort = request('sort');
    $currentDir = request('direction', 'asc');
    
    // Determine active state
    $isActive = $currentSort === $column;
    
    // Toggle direction if clicking the same column, otherwise default to asc
    $nextDir = $isActive && $currentDir === 'asc' ? 'desc' : 'asc';
    
    // Build query parameters preserving existing ones like search, per_page, etc., and include route parameters
    $url = route($route, array_merge(request()->route()->parameters(), request()->query(), [
        'sort' => $column, 
        'direction' => $nextDir,
        'page' => 1 // Reset to page 1 on sort change
    ]));
@endphp

<th class="{{ $align === 'right' ? 'text-right' : ($align === 'center' ? 'text-center' : 'text-left') }}">
    <a href="{{ $url }}" class="inline-flex items-center gap-1 group hover:text-[var(--color-brand)] transition-colors">
        {{ $label }}
        
        <span class="inline-flex flex-col w-3 h-3 justify-center items-center">
            @if($isActive)
                @if($currentDir === 'asc')
                    <svg class="w-3 h-3 text-[var(--color-brand)]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.293 9.707l4-4a1 1 0 011.414 0l4 4"/>
                    </svg>
                @else
                    <svg class="w-3 h-3 text-[var(--color-brand)]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M14.707 10.293l-4 4a1 1 0 01-1.414 0l-4-4"/>
                    </svg>
                @endif
            @else
                <svg class="w-3 h-3 text-[var(--color-text-muted)] opacity-0 group-hover:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            @endif
        </span>
    </a>
</th>
