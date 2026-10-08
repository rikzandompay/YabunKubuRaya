<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    public const CREATED_AT = 'dibuat_pada';

    public const UPDATED_AT = 'diperbarui_pada';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'kata_sandi',
        'token_ingat',
    ];

    /**
     * Get the name of the password attribute for authentication.
     */
    public function getAuthPasswordName(): string
    {
        return 'kata_sandi';
    }

    /**
     * Get the name of the remember token attribute.
     */
    public function getRememberTokenName(): string
    {
        return 'token_ingat';
    }

    /**
     * Compat accessor for name.
     */
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }

    /**
     * Compat mutator for name.
     */
    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }

    /**
     * Compat mutator for password.
     */
    public function setPasswordAttribute(?string $value): void
    {
        $this->attributes['kata_sandi'] = $value;
    }

    /**
     * Determine whether the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->peran, ['admin', 'superadmin'], true);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_diverifikasi_pada' => 'datetime',
            'kata_sandi' => 'hashed',
        ];
    }
}
