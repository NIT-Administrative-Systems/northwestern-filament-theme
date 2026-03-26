@php
    /** @var \Northwestern\FilamentTheme\ImpersonationBanner\ImpersonationBannerConfig $config */
    $leaveUrl = $config->resolveLeaveUrl();
@endphp

<style>
    :root {
        --nu-impersonate-banner-height: 90px;
    }

    @media (min-width: 640px) {
        :root {
            --nu-impersonate-banner-height: 56px;
        }
    }

    .nu-impersonate-banner {
        position: sticky;
        top: 0;
        z-index: 29;
        background-color: oklch(0.5400 0.2020 21.239);
        font-family: var(--nu-font-body);
    }

    .fi-topbar-ctn {
        top: var(--nu-impersonate-banner-height);
    }

    .nu-impersonate-banner-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: var(--nu-impersonate-banner-height);
        max-width: 80rem;
        margin: 0 auto;
        padding: 0 1rem;
    }

    @media (min-width: 640px) {
        .nu-impersonate-banner-inner {
            flex-direction: row;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0 1.5rem;
        }
    }

    .nu-impersonate-banner-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .nu-impersonate-banner-icon {
        width: 1.25rem;
        height: 1.25rem;
        flex-shrink: 0;
        color: oklch(85% 0.06 21.239);
    }

    .nu-impersonate-banner-text {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 0 0.5rem;
        text-align: center;
        font-size: 0.875rem;
        font-weight: 500;
        color: #fff;
    }

    @media (min-width: 640px) {
        .nu-impersonate-banner-text {
            text-align: left;
        }
    }

    .nu-impersonate-banner-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
        padding: 0.5rem 0.875rem;
        font-family: var(--nu-font-body);
        font-size: 0.875rem;
        font-weight: 600;
        color: oklch(0.5400 0.2020 21.239);
        background-color: #fff;
        border: none;
        box-shadow: 0 1px 2px rgb(0 0 0 / 5%);
        cursor: pointer;
        transition: background-color 150ms;
    }

    .nu-impersonate-banner-btn:hover {
        background-color: oklch(95% 0.02 21.239);
    }

    @media (prefers-reduced-motion: reduce) {
        .nu-impersonate-banner-btn {
            transition: none;
        }
    }

    .nu-impersonate-banner-btn:focus-visible {
        outline: 2px solid #fff;
        outline-offset: 2px;
    }

    .nu-impersonate-banner-btn-icon {
        width: 1rem;
        height: 1rem;
    }

    @media print {
        .nu-impersonate-banner {
            display: none;
        }
    }
</style>

<div class="nu-impersonate-banner" role="alert">
    <div class="nu-impersonate-banner-inner">
        <div class="nu-impersonate-banner-info">
            <svg class="nu-impersonate-banner-icon"
                 aria-hidden="true"
                 xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 20 20"
                 fill="currentColor">
                <path fill-rule="evenodd"
                      d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                      clip-rule="evenodd" />
            </svg>
            <div class="nu-impersonate-banner-text">
                {{ $config->resolveLabel() }}
            </div>
        </div>
        @if ($leaveUrl)
            <form method="{{ $config->leaveMethod === "GET" ? "GET" : "POST" }}" action="{{ $leaveUrl }}">
                @if ($config->leaveMethod !== "GET")
                    @csrf
                    @if (!in_array($config->leaveMethod, ["GET", "POST"]))
                        @method($config->leaveMethod)
                    @endif
                @endif
                <button class="nu-impersonate-banner-btn" type="submit">
                    <svg class="nu-impersonate-banner-btn-icon"
                         aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                              clip-rule="evenodd" />
                        <path fill-rule="evenodd"
                              d="M19 10a.75.75 0 0 0-.75-.75H8.704l1.048-.943a.75.75 0 1 0-1.004-1.114l-2.5 2.25a.75.75 0 0 0 0 1.114l2.5 2.25a.75.75 0 1 0 1.004-1.114l-1.048-.943h9.546A.75.75 0 0 0 19 10Z"
                              clip-rule="evenodd" />
                    </svg>
                    {{ $config->resolveLeaveLabel() }}
                </button>
            </form>
        @endif
    </div>
</div>
