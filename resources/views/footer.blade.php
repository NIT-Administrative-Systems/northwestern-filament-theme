@php
    use Northwestern\FilamentTheme\Footer\FooterStylesheet;
    use Northwestern\FilamentTheme\Footer\RequiredLink;

    /** @var \Northwestern\FilamentTheme\Footer\FooterConfig $config */
    // Standalone markup: it must not call Filament, auth or the database, so error pages can render it.
    $office = $config->office();
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

                <div class="nu-footer-section">
                    <h2>Connect</h2>
                    <ul class="nu-footer-social">
                        <li>
                            <a href="https://www.facebook.com/NorthwesternU">
                                <svg aria-hidden="true"
                                     focusable="false"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 40 41"
                                     fill="none">
                                    <ellipse cx="20"
                                             cy="20.0074"
                                             rx="20"
                                             ry="20.0074"
                                             fill="currentColor" />
                                    <path d="M40 20.0074C40 8.96329 31.04 0 20 0C8.96 0 0 8.96329 0 20.0074C0 29.6909 6.88 37.7539 16 39.6146V26.0096H12V20.0074H16V15.0055C16 11.1441 19.14 8.00294 23 8.00294H28V14.0051H24C22.9 14.0051 22 14.9055 22 16.0059V20.0074H28V26.0096H22V39.9147C32.1 38.9143 40 30.3912 40 20.0074Z"
                                          fill="#fff" />
                                </svg>
                                <span class="nu-sr-only">Northwestern University on Facebook</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://instagram.com/northwesternu">
                                <svg aria-hidden="true"
                                     focusable="false"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 40 40"
                                     fill="none">
                                    <rect width="40"
                                          height="40"
                                          rx="20"
                                          fill="#fff" />
                                    <path d="M20.2927 8.29254C23.9024 8.29254 24.2927 8.29254 25.7561 8.3901C26.6244 8.42912 27.4732 8.59498 28.2927 8.87791C28.8683 9.11205 29.3951 9.434 29.8536 9.85352C30.3219 10.273 30.6634 10.8096 30.8293 11.4145C31.1317 12.2242 31.2878 13.0828 31.3171 13.9511C31.4146 15.4145 31.4146 15.8047 31.4146 19.4145C31.4146 23.0242 31.4146 23.4145 31.3171 24.8779C31.278 25.7462 31.1122 26.595 30.8293 27.4145C30.5951 27.9901 30.2732 28.5169 29.8536 28.9755C29.4341 29.4438 28.8975 29.7852 28.2927 29.9511C27.4829 30.2535 26.6244 30.4096 25.7561 30.4389C24.2927 30.5364 23.9024 30.5364 20.2927 30.5364C16.6829 30.5364 16.2927 30.5364 14.8293 30.4389C13.961 30.3999 13.1122 30.234 12.2927 29.9511C11.7171 29.7169 11.1902 29.395 10.7317 28.9755C10.2634 28.556 9.92194 28.0194 9.75608 27.4145C9.45364 26.6047 9.29755 25.7462 9.26828 24.8779C9.17072 23.4145 9.17072 23.0242 9.17072 19.4145C9.17072 15.8047 9.17072 15.4145 9.26828 13.9511C9.3073 13.0828 9.47316 12.234 9.75608 11.4145C9.99023 10.8389 10.3122 10.3121 10.7317 9.85352C11.1512 9.38522 11.6878 9.04376 12.2927 8.87791C13.1024 8.57547 13.961 8.41937 14.8293 8.3901C16.1951 8.29254 16.6829 8.29254 20.2927 8.29254ZM20.2927 5.85352C16.5853 5.85352 16.1951 5.85352 14.7317 5.95108C13.6097 5.99986 12.4878 6.19498 11.4146 6.53644C10.5268 6.86815 9.72681 7.40474 9.07315 8.09742C8.39998 8.76083 7.87315 9.56083 7.51218 10.4389C7.12193 11.5023 6.92681 12.6242 6.92681 13.756C6.82925 15.2194 6.82925 15.6096 6.82925 19.3169C6.82925 23.0242 6.82925 23.4145 6.92681 24.8779C6.97559 25.9999 7.17071 27.1218 7.51218 28.195C7.84389 29.0828 8.38047 29.8828 9.07315 30.5364C9.73657 31.2096 10.5366 31.7364 11.4146 32.0974C12.478 32.4877 13.6 32.6828 14.7317 32.6828C16.1951 32.7803 16.5853 32.7803 20.2927 32.7803C24 32.7803 24.3902 32.7803 25.8536 32.6828C26.9756 32.634 28.0975 32.4389 29.1707 32.0974C30.0585 31.7657 30.8585 31.2291 31.5122 30.5364C32.1853 29.873 32.7122 29.073 33.0732 28.195C33.4634 27.1316 33.6585 26.0096 33.6585 24.8779C33.7561 23.4145 33.7561 23.0242 33.7561 19.3169C33.7561 15.6096 33.7561 15.2194 33.6585 13.756C33.6097 12.634 33.4146 11.5121 33.0732 10.4389C32.7415 9.55108 32.2049 8.75108 31.5122 8.09742C30.8488 7.42425 30.0488 6.89742 29.1707 6.53644C28.1073 6.1462 26.9854 5.95108 25.8536 5.95108C24.3902 5.85352 23.9024 5.85352 20.2927 5.85352Z"
                                          fill="#4E2983" />
                                    <path d="M20.2927 12.3906C16.4683 12.3906 13.3659 15.4931 13.3659 19.3175C13.3659 23.1418 16.4683 26.2443 20.2927 26.2443C24.1171 26.2443 27.2195 23.1418 27.2195 19.3175C27.2 15.5028 24.1073 12.4101 20.2927 12.3906ZM20.2927 23.8053C17.8147 23.8053 15.8049 21.7955 15.8049 19.3175C15.8049 16.8394 17.8147 14.8296 20.2927 14.8296C22.7708 14.8296 24.7805 16.8394 24.7805 19.3175C24.7512 21.7857 22.761 23.776 20.2927 23.8053Z"
                                          fill="#4E2983" />
                                    <path d="M27.4146 13.8532C28.3306 13.8532 29.0732 13.1107 29.0732 12.1947C29.0732 11.2787 28.3306 10.5361 27.4146 10.5361C26.4987 10.5361 25.7561 11.2787 25.7561 12.1947C25.7561 13.1107 26.4987 13.8532 27.4146 13.8532Z"
                                          fill="#4E2983" />

                                </svg>
                                <span class="nu-sr-only">Northwestern University on Instagram</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/user/NorthwesternU">
                                <svg aria-hidden="true"
                                     focusable="false"
                                     xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 41 41"
                                     fill="none">
                                    <rect width="41"
                                          height="41"
                                          rx="20.5"
                                          fill="#fff" />
                                    <path fill-rule="evenodd"
                                          clip-rule="evenodd"
                                          d="M29.3964 12.9762C30.3698 13.2562 31.119 14.0265 31.3711 14.9998C31.7002 16.8134 31.8612 18.655 31.8472 20.4966C31.8612 22.3452 31.7002 24.1869 31.3711 26.0005C31.112 26.9878 30.3487 27.758 29.3614 28.0241C27.5898 28.5003 20.4825 28.5003 20.4825 28.5003C20.4825 28.5003 13.3751 28.5003 11.6036 28.0241C10.6372 27.744 9.88801 26.9808 9.62892 26.0075C9.29981 24.1939 9.13875 22.3522 9.15276 20.5036C9.13875 18.655 9.29981 16.8134 9.62892 14.9998C9.89501 14.0195 10.6583 13.2492 11.6386 12.9762C13.4102 12.5 20.5175 12.5 20.5175 12.5C20.5175 12.5 27.6248 12.5 29.3964 12.9762ZM24.1797 20.223L18.1507 23.6471V16.7988L24.1797 20.223Z"
                                          fill="currentColor" />

                                </svg>
                                <span class="nu-sr-only">Northwestern University on YouTube</span>
                            </a>
                        </li>
                    </ul>
                </div>

                @if ($config->links !== [])
                    <div class="nu-footer-section">
                        <h2>Quick Links</h2>
                        <ul>
                            @foreach ($config->links as $label => $url)
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
