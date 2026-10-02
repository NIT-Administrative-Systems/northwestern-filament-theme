<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Footer;

/**
 * The networks the footer's "Connect" section has icons for.
 *
 * The values are the keys of `footer.social` in the theme's config and match
 * the network classes in Department Templates 4.0's footer. dept 4.0's Storify
 * icon is left out: the service shut down in 2018.
 */
enum SocialNetwork: string
{
    case Bluesky = 'bluesky';
    case Facebook = 'facebook';
    case Flickr = 'flickr';
    case Instagram = 'instagram';
    case LinkedIn = 'linkedin';
    case Pinterest = 'pinterest';
    case Rss = 'rss';
    case Spotify = 'spotify';
    case Threads = 'threads';
    case TikTok = 'tiktok';
    case Tumblr = 'tumblr';
    case Vimeo = 'vimeo';
    case WordPress = 'wordpress';
    case X = 'x';
    case YouTube = 'youtube';

    /** Northwestern University's own accounts, the default for `footer.social`. */
    public const array NORTHWESTERN = [
        'facebook' => 'https://www.facebook.com/NorthwesternU',
        'instagram' => 'https://instagram.com/northwesternu',
        'youtube' => 'https://www.youtube.com/user/NorthwesternU',
    ];

    public function label(): string
    {
        return match ($this) {
            self::Bluesky => 'Bluesky',
            self::Facebook => 'Facebook',
            self::Flickr => 'Flickr',
            self::Instagram => 'Instagram',
            self::LinkedIn => 'LinkedIn',
            self::Pinterest => 'Pinterest',
            self::Rss => 'RSS',
            self::Spotify => 'Spotify',
            self::Threads => 'Threads',
            self::TikTok => 'TikTok',
            self::Tumblr => 'Tumblr',
            self::Vimeo => 'Vimeo',
            self::WordPress => 'WordPress',
            self::X => 'X',
            self::YouTube => 'YouTube',
        };
    }

    /**
     * The link's accessible name. Northwestern's own accounts say whose they
     * are; a unit's accounts are named by network, under the "Connect" heading.
     */
    public function accessibleName(string $url): string
    {
        return (self::NORTHWESTERN[$this->value] ?? null) === $url
            ? "Northwestern University on {$this->label()}"
            : $this->label();
    }
}
