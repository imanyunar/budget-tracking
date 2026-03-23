@props(['name' => 'circle', 'class' => 'w-5 h-5'])

@php
    $path = base_path("node_modules/lucide-static/icons/{$name}.svg");
    $svgContent = '';
    if (file_exists($path)) {
        $content = file_get_contents($path);
        // Remove existing class
        $content = preg_replace('/class="[^"]*"/', '', $content);
        // Inject our class
        $svgContent = str_replace('<svg ', '<svg class="' . $class . '" ', $content);
    }
@endphp

{!! $svgContent !!}
