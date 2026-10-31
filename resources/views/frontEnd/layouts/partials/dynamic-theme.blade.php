@php
    $theme = \App\Support\ThemeColors::fromSetting($generalsetting ?? null);
@endphp
<style id="dynamic-theme">
:root {
    --primary: {{ $theme['primary'] }};
    --primary-dark: {{ $theme['primary_dark'] }};
    --primary-light: {{ $theme['primary_light'] }};
    --secondary: {{ $theme['secondary'] }};
    --footer-bg: {{ $theme['footer'] }};
    --copyright-bg: {{ $theme['copyright'] }};
    --primary-rgb: {{ $theme['primary_rgb'] }};
}
</style>
