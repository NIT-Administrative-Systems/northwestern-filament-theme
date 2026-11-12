<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

/**
 * Filament's sign-in page with the seeded demo account filled in.
 */
class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => 'demo@northwestern.edu',
            'password' => 'password',
        ]);
    }
}
