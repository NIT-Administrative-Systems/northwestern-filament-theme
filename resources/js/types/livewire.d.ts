type LivewireCommitHookPayload = {
    component: { el: HTMLElement };
    succeed: (callback: () => void) => void;
    fail: (callback: () => void) => void;
};

declare global {
    interface Window {
        Livewire?: {
            hook: (name: 'commit', callback: (payload: LivewireCommitHookPayload) => void) => void;
        };
    }
}

export {};
