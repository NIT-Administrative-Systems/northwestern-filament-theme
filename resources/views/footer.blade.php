@php
    use Northwestern\FilamentTheme\Footer\FooterStylesheet;
    use Northwestern\FilamentTheme\Footer\RequiredLink;
    use Northwestern\FilamentTheme\Footer\SocialNetwork;

    /** @var \Northwestern\FilamentTheme\Footer\FooterConfig $config */
    // Standalone markup: it must not call Filament, auth or the database, so error pages can render it.
    $office = $config->office();
    $links = $config->quickLinks();
    $social = $config->social();
@endphp

<style>
    {!! FooterStylesheet::css() !!}
</style>

<footer class="nu-footer">
    <div class="nu-footer-main">
        <div class="nu-footer-container">
            <a class="nu-footer-wordmark" href="https://www.northwestern.edu/">
                @include("northwestern-filament-theme::wordmark", [
                    "variant" => "northwestern-university",
                    "label" => "Northwestern University",
                ])
            </a>

            @if ($office["name"])
                <h2 class="nu-footer-unit">{{ $office["name"] }}</h2>
            @endif

            <div class="nu-footer-columns">
                <address class="nu-footer-section nu-footer-contact">
                    @if ($office["addr"] || $office["city"])
                        <div class="nu-footer-contact-item">
                            <svg class="nu-footer-icon"
                                 aria-hidden="true"
                                 focusable="false"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 21.9 31"
                                 preserveAspectRatio="xMinYMin meet"
                                 fill="currentColor">
                                <path
                                      d="M10.9,0C4.9,0,0,5.5,0,10.9,0,20.1,10.9,31,10.9,31S21.8,20.1,21.8,10.9A11.19,11.19,0,0,0,10.9,0ZM6.1,10.9a4.8,4.8,0,0,1,4.8-4.8,4.87,4.87,0,0,1,4.8,4.8,4.8,4.8,0,1,1-9.6,0Z" />
                            </svg>
                            <span class="nu-sr-only">Address</span>
                            <p>
                                @if ($office["addr"])
                                    {{ $office["addr"] }}<br>
                                @endif
                                {{ $office["city"] }}
                            </p>
                        </div>
                    @endif

                    @if ($office["phone"])
                        <div class="nu-footer-contact-item">
                            <svg class="nu-footer-icon"
                                 aria-hidden="true"
                                 focusable="false"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 9.5 17.8"
                                 preserveAspectRatio="xMinYMin meet"
                                 fill="currentColor">
                                <path
                                      d="M8,0H1.5A1.54,1.54,0,0,0,0,1.5V16.3a1.54,1.54,0,0,0,1.5,1.5H8a1.54,1.54,0,0,0,1.5-1.5V1.5A1.47,1.47,0,0,0,8,0ZM3.9,1.2H5.7a.32.32,0,0,1,.3.3.27.27,0,0,1-.3.3H3.9a.27.27,0,0,1-.3-.3A.27.27,0,0,1,3.9,1.2Zm.9,16a.9.9,0,1,1,.9-.9A.9.9,0,0,1,4.8,17.2Zm4.1-2.4H.6V3H8.9Z" />
                            </svg>
                            <span class="nu-sr-only">Phone number</span>
                            <p>{{ $office["phone"] }}</p>
                        </div>
                    @endif

                    @if ($office["fax"])
                        <div class="nu-footer-contact-item">
                            <svg class="nu-footer-icon"
                                 aria-hidden="true"
                                 focusable="false"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 612 545.81"
                                 preserveAspectRatio="xMinYMin meet"
                                 fill="currentColor">
                                <path d="M587,125.52H512.08V58.07a25,25,0,0,0-25-25H124.9a25,25,0,0,0-25,25v67.44H25a25,25,0,0,0-25,25V395.3a25,25,0,0,0,25,25H99.92V551.93c0,8.33,1.68,14.54,8.12,20.43A24.07,24.07,0,0,0,118.15,578c3.62,1,7.2.93,10.86,0.93H483c3.66,0,7.24.09,10.87-.93A24,24,0,0,0,504,572.34c6.44-5.9,8.11-12.12,8.11-20.45V420.28H587a25,25,0,0,0,25-25V150.5A25,25,0,0,0,587,125.52ZM62.45,213.57a21.86,21.86,0,1,1,21.86-21.86A21.86,21.86,0,0,1,62.45,213.57ZM487.1,553.92H124.9V356.58H487.1V553.92h0Zm0-428.4H124.9V83.05a25,25,0,0,1,25-25H462.12a25,25,0,0,1,25,25Q487.1,104.29,487.1,125.52Z"
                                      transform="translate(0 -33.09)" />
                            </svg>
                            <span class="nu-sr-only">Fax number</span>
                            <p>{{ $office["fax"] }}</p>
                        </div>
                    @endif

                    @if ($office["email"])
                        <div class="nu-footer-contact-item">
                            <svg class="nu-footer-icon"
                                 aria-hidden="true"
                                 focusable="false"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 31.8 31.8"
                                 preserveAspectRatio="xMinYMin meet"
                                 fill="currentColor">
                                <path
                                      d="M1.3,12.5A2,2,0,0,0,.8,16l6.5,4.7L30.4,2.6,10,22.7l8.9,6.5a2.36,2.36,0,0,0,2,.3,2,2,0,0,0,1.4-1.4l9.5-26a.78.78,0,0,0-.2-.8.78.78,0,0,0-.8-.2Z" />
                                <path
                                      d="M5.8,22.2l.1.3,1.3,6.9a1.44,1.44,0,0,0,.9,1.1,1.6,1.6,0,0,0,1.5-.1,39.18,39.18,0,0,0,4-2.7Z" />
                            </svg>
                            <span class="nu-sr-only">Email address</span>
                            <p><a href="mailto:{{ $office["email"] }}">{{ $office["email"] }}</a></p>
                        </div>
                    @endif
                </address>

                @if ($social !== [])
                    <div class="nu-footer-section">
                        <h2>Connect</h2>
                        <ul class="nu-footer-social">
                            @foreach ($social as $network => $url)
                                @php($name = SocialNetwork::from($network)->accessibleName($url))
                                <li>
                                    <a href="{{ $url }}">
                                        @include("northwestern-filament-theme::social-icon", [
                                            "network" => $network,
                                        ])
                                        <span class="nu-sr-only">{{ $name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($links !== [])
                    <div class="nu-footer-section">
                        <h2>Quick Links</h2>
                        <ul>
                            @foreach ($links as $label => $url)
                                <li><a href="{{ $url }}">{{ $label }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="nu-footer-section">
                    <h2>Northwestern Resources</h2>
                    <ul>
                        @foreach (RequiredLink::RESOURCES as $link)
                            <li><a href="{{ $link->url() }}">{{ $link->label() }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="nu-footer-bottom">
        <div class="nu-footer-container">
            <ul>
                <li class="nu-footer-copyright">&copy; {{ date("Y") }} Northwestern University</li>
                @foreach (RequiredLink::LEGAL as $link)
                    <li><a href="{{ $link->url() }}">{{ $link->label() }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
