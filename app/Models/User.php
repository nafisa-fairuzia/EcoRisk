<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'alamat',
        'email',
        'password',
        'role',
        'zona_wilayah',
        'telepon',
        'status',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }

    // Role display label
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator Sistem',
            'petugas' => 'Petugas Lapangan',
            'warga' => 'Warga Masyarakat',
            'mitra_daur_ulang' => 'Mitra Daur Ulang',
            default => 'Pengguna',
        };
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo_path
            ? Storage::disk('public')->url($this->profile_photo_path)
            : null;
    }

    // Inisial untuk avatar (contoh: "Hendra Wijaya" => "HW")
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper($w[0] ?? '');
        }

        return $initials ?: 'US';
    }

    public function reports(): HasMany
    {
        return $this->hasMany(WasteReport::class, 'user_id');
    }

    public function handledReports(): HasMany
    {
        return $this->hasMany(WasteReport::class, 'petugas_id');
    }

    public function sortingRecords(): HasMany
    {
        return $this->hasMany(SortingRecord::class, 'petugas_id');
    }

    public function distributionRecords(): HasMany
    {
        return $this->hasMany(DistributionRecord::class, 'petugas_id');
    }
}
