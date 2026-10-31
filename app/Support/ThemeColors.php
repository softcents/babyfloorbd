<?php

namespace App\Support;

use App\Models\GeneralSetting;

class ThemeColors
{
    public static function fromSetting(?GeneralSetting $setting = null): array
    {
        $gs = $setting ?? GeneralSetting::where('status', 1)->first();

        $primary   = self::normalizeHex($gs->primary_color ?? null, '#df2d4d');
        $secondary = self::normalizeHex($gs->secodery_color ?? null, '#198754');
        $footer    = self::normalizeHex($gs->footer_color ?? null, '#222222');
        $copyright = self::normalizeHex($gs->copyright_color ?? null, '#111111');

        return [
            'primary'       => $primary,
            'primary_dark'  => self::darken($primary, 18),
            'primary_light' => self::lighten($primary, 12),
            'secondary'     => $secondary,
            'footer'        => $footer,
            'copyright'     => $copyright,
            'primary_rgb'   => self::toRgbString($primary),
        ];
    }

    public static function normalizeHex(?string $hex, string $default): string
    {
        $hex = trim((string) $hex);
        if ($hex === '' || ! preg_match('/^#?([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $hex)) {
            return $default;
        }

        if ($hex[0] !== '#') {
            $hex = '#' . $hex;
        }

        if (strlen($hex) === 4) {
            $hex = '#' . $hex[1] . $hex[1] . $hex[2] . $hex[2] . $hex[3] . $hex[3];
        }

        return strtolower($hex);
    }

    public static function darken(string $hex, int $percent = 18): string
    {
        return self::adjustBrightness($hex, -$percent);
    }

    public static function lighten(string $hex, int $percent = 12): string
    {
        return self::adjustBrightness($hex, $percent);
    }

    public static function toRgbString(string $hex): string
    {
        $hex = ltrim(self::normalizeHex($hex, '#000000'), '#');

        return sprintf(
            '%d, %d, %d',
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
    }

    private static function adjustBrightness(string $hex, int $percent): string
    {
        $hex = ltrim(self::normalizeHex($hex, '#000000'), '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + (int) round(255 * ($percent / 100))));
        $g = max(0, min(255, $g + (int) round(255 * ($percent / 100))));
        $b = max(0, min(255, $b + (int) round(255 * ($percent / 100))));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
