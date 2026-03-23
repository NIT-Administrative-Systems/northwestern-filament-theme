@php
    /** @var \Northwestern\FilamentTheme\Footer\FooterConfig $config */
    $officeName = $config->officeName ?? config("northwestern-theme.office.name", "Information Technology");
    $officeAddr = $config->officeAddr ?? config("northwestern-theme.office.addr", "1800 Sherman Ave");
    $officeCity = $config->officeCity ?? config("northwestern-theme.office.city", "Evanston, IL 60201");
    $officePhone = $config->officePhone ?? config("northwestern-theme.office.phone", "847-491-4357 (1-HELP)");
    $officeEmail = $config->officeEmail ?? config("northwestern-theme.office.email", "consultant@northwestern.edu");
@endphp

<style>
    .nu-footer {
        background-color: var(--nu-purple-100);
        color: #fff;
        font-family: var(--nu-font-body);
        font-size: 0.875rem;
        line-height: 1.6;
        min-height: 350px;
        padding-top: 3em;
        padding-bottom: 2em;
    }

    .nu-footer a {
        color: #fff;
        text-decoration: underline;
    }

    .nu-footer a:hover {
        text-decoration: none;
        color: #fff;
    }

    .nu-footer-grid {
        display: grid;
        grid-template-columns: 3fr 3fr 2fr;
        gap: 30px;
        max-width: 66.666%;
        margin: 0 auto;
        padding: 0 15px;
    }

    .nu-branding {
        max-width: 170px;
        height: auto;
        padding-bottom: 1rem;
    }

    .nu-footer-links {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nu-footer-links li {
        padding-bottom: 0.8rem;
    }

    .nu-contact-group {
        display: flex;
        justify-content: flex-start;
        margin: 0 0 2rem;
    }

    .nu-contact-group+.nu-contact-group {
        margin-bottom: 1.5rem;
    }

    .nu-address {
        font-style: normal;
    }

    .nu-address strong,
    .nu-address span {
        display: block;
        padding-bottom: 0.8rem;
    }

    .nu-address span:last-child {
        padding-bottom: 0;
    }

    .nu-contact-group dd {
        margin: 0;
    }

    .nu-pin {
        background-repeat: no-repeat;
        background-size: 18px 24px;
        height: 24px;
        width: 18px;
        min-width: 18px;
        margin-right: 1rem;
    }

    .nu-pin-address {
        background-image: url('https://common.northwestern.edu/v8/css/images/icons/pin-drop.svg');
    }

    .nu-pin-phone {
        background-image: url('https://common.northwestern.edu/v8/css/images/icons/mobile-phone.svg');
    }

    .nu-pin-email {
        background-image: url('https://common.northwestern.edu/v8/css/images/icons/email.svg');
    }

    .nu-footer .nu-footer-col p {
        margin: 0 0 1rem;
    }

    .nu-footer .nu-social {
        border: 1px solid #fff;
        display: inline-block;
        font-size: 0;
        height: 39px;
        margin: 4px 6px;
        overflow: hidden;
        text-indent: -9999px;
        text-decoration: none;
        transition: background 0.3s ease 0s;
        vertical-align: bottom;
        width: 39px;
    }

    .nu-footer .nu-social-facebook {
        background: url('https://common.northwestern.edu/v8/css/images/icons/social-media-icons.png');
        background-position: 0 0;
    }

    .nu-footer .nu-social-facebook:hover {
        background-position: 0 39px;
    }

    .nu-footer .nu-social-instagram {
        background-image: url('https://common.northwestern.edu/v8/css/images/icons/social-media-icons.png');
        background-position: -78px 0;
    }

    .nu-footer .nu-social-instagram:hover {
        background-position: -78px 39px;
    }

    .nu-footer .nu-social-youtube {
        background-image: url('https://common.northwestern.edu/v8/css/images/icons/social-media-icons.png');
        background-position: -156px 0;
    }

    .nu-footer .nu-social-youtube:hover {
        background-position: -156px 39px;
    }

    @media (width <=768px) {
        .nu-footer-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (width >=992px) {
        .nu-pin {
            margin-left: -1rem;
        }
    }

    .nu-footer .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }

    @media print {
        .nu-footer {
            display: none;
        }
    }
</style>

<footer class="nu-footer">
    <div class="nu-footer-grid">
        <div class="nu-footer-col">
            <a href="https://www.northwestern.edu" rel="noopener">
                <img class="nu-branding"
                     src="https://common.northwestern.edu/v8/css/images/northwestern-university.svg"
                     alt="Northwestern University">
            </a>

            <ul class="nu-footer-links">
                <li>&copy; {{ date("Y") }} Northwestern University</li>
                <li><a href="https://www.northwestern.edu/contact.html" rel="noopener">Contact Northwestern
                        University</a></li>
                <li><a href="https://www.northwestern.edu/hr/careers/" rel="noopener">Careers</a></li>
                <li><a href="https://www.northwestern.edu/disclaimer.html" rel="noopener">Disclaimer</a></li>
                <li><a href="https://www.northwestern.edu/emergency/index.html" rel="noopener">Campus Emergency
                        Information</a></li>
                <li><a href="https://policies.northwestern.edu/" rel="noopener">University Policies</a></li>
                <li><a href="https://www.northwestern.edu/accessibility/about/report-accessibility-issue.html"
                       rel="noopener">Report an
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
            <a class="nu-social nu-social-facebook"
               href="https://www.facebook.com/NorthwesternU"
               aria-label="Northwestern University on Facebook"
               rel="noopener">Facebook</a>
            <a class="nu-social nu-social-instagram"
               href="https://instagram.com/northwesternu"
               aria-label="Northwestern University on Instagram"
               rel="noopener">Instagram</a>
            <a class="nu-social nu-social-youtube"
               href="https://www.youtube.com/user/NorthwesternU"
               aria-label="Northwestern University on YouTube"
               rel="noopener">YouTube</a>
        </div>
    </div>
</footer>
