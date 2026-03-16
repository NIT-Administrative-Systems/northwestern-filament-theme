@php
    /** @var \Northwestern\FilamentTheme\Footer\FooterConfig $config */
    $officeName = $config->officeName ?? config('northwestern-theme.office.name', 'Information Technology');
    $officeAddr = $config->officeAddr ?? config('northwestern-theme.office.addr', '1800 Sherman Ave');
    $officeCity = $config->officeCity ?? config('northwestern-theme.office.city', 'Evanston, IL 60201');
    $officePhone = $config->officePhone ?? config('northwestern-theme.office.phone', '847-491-4357 (1-HELP)');
    $officeEmail = $config->officeEmail ?? config('northwestern-theme.office.email', 'consultant@northwestern.edu');
@endphp

<footer class="nu-footer">
    <div class="nu-footer-grid">
        <div class="nu-footer-col">
            <a href="https://www.northwestern.edu">
                <img
                    src="https://common.northwestern.edu/v8/css/images/northwestern-university.svg"
                    alt="Northwestern University"
                    class="nu-branding"
                >
            </a>

            <ul class="nu-footer-links">
                <li>&copy; {{ date('Y') }} Northwestern University</li>
                <li><a href="https://www.northwestern.edu/contact.html">Contact Northwestern University</a></li>
                <li><a href="https://www.northwestern.edu/hr/careers/">Careers</a></li>
                <li><a href="https://www.northwestern.edu/disclaimer.html">Disclaimer</a></li>
                <li><a href="https://www.northwestern.edu/emergency/index.html">Campus Emergency Information</a></li>
                <li><a href="https://policies.northwestern.edu/">University Policies</a></li>
                <li><a href="https://www.northwestern.edu/accessibility/about/report-accessibility-issue.html">Report an
                        Accessibility Issue</a></li>
            </ul>
        </div>

        <div class="nu-footer-col">
            <dl class="nu-contact-group">
                <dt class="nu-pin nu-pin-address"><span class="sr-only">Office Address</span></dt>
                <dd>
                    <address class="nu-address">
                        <strong>{{ $officeName }}</strong>
                        <span>{{ $officeAddr }}</span>
                        <span>{{ $officeCity }}</span>
                    </address>
                </dd>
            </dl>

            <dl class="nu-contact-group">
                <dt class="nu-pin nu-pin-phone"><span class="sr-only">Phone Number</span></dt>
                <dd>{{ $officePhone }}</dd>
            </dl>

            <dl class="nu-contact-group">
                <dt class="nu-pin nu-pin-email"><span class="sr-only">Email Address</span></dt>
                <dd><a href="mailto:{{ $officeEmail }}">{{ $officeEmail }}</a></dd>
            </dl>
        </div>

        <div class="nu-footer-col">
            <p>Social Media</p>
            <a href="https://www.facebook.com/NorthwesternU" class="nu-social nu-social-facebook">Facebook</a>
            <a href="https://instagram.com/northwesternu" class="nu-social nu-social-instagram">Instagram</a>
            <a href="https://www.youtube.com/user/NorthwesternU" class="nu-social nu-social-youtube">YouTube</a>
        </div>
    </div>
</footer>
