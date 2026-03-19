@php
    /** @var \Northwestern\FilamentTheme\EnvironmentIndicator\EnvironmentIndicatorConfig $config */
@endphp

<div class="nu-env-indicator" role="status">
    <svg class="nu-env-indicator-icon"
         aria-hidden="true"
         xmlns="http://www.w3.org/2000/svg"
         viewBox="0 0 20 20"
         fill="currentColor">
        <path fill-rule="evenodd"
              d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
              clip-rule="evenodd" />
    </svg>
    {{ $config->resolveLabel() }}
</div>

<style>
    .fi-topbar {
        border-top: 4px solid var(--nu-gold);
    }

    .nu-env-indicator {
        display: none;
        align-items: center;
        gap: 0.375rem;
        padding: 0.3rem 0.75rem;
        font-family: var(--nu-font-body);
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        line-height: 1.5;
        color: var(--nu-black-100);
        background-color: var(--nu-gold);
        border: 2px solid var(--nu-black-100);
        border-radius: 0 !important;
        box-shadow: none;
        white-space: nowrap;
    }

    .nu-env-indicator-icon {
        width: 1.125rem;
        height: 1.125rem;
        flex-shrink: 0;
        opacity: 0.8;
        align-self: center;
        display: block;
    }

    @media (min-width: 640px) {
        .nu-env-indicator {
            display: inline-flex !important;
        }
    }

    .dark .nu-env-indicator {
        background-color: #A76616;
        color: #fff;
        border-color: rgb(255 255 255 / 25%);
    }

    .dark .fi-topbar {
        border-top-color: #A76616;
    }
</style>
