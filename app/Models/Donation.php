<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Donation extends Model
{
    protected $fillable = [
        'collection_id',
        'donor_name',
        'lakou',
        'lokalite',
        'amount',
        'is_paid',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_paid' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Donation $donation): void {
            $donation->lakou = self::cleanName($donation->lakou);
            $donation->lokalite = self::cleanName($donation->lokalite);
            $donation->notes = filled($donation->notes) ? trim($donation->notes) : null;
        });

        static::saved(function (Donation $donation): void {
            self::syncSuggestion(Lakou::class, $donation->lakou);
            self::syncSuggestion(Locality::class, $donation->lokalite);
        });

        static::created(fn (Donation $donation) => self::audit($donation, 'created', null, $donation->fresh()->toArray()));

        static::updated(function (Donation $donation): void {
            self::audit(
                $donation,
                'updated',
                array_intersect_key($donation->getOriginal(), $donation->getChanges()),
                $donation->getChanges(),
            );
        });

        static::deleted(fn (Donation $donation) => self::audit($donation, 'deleted', $donation->toArray(), null));
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    private static function cleanName(?string $value): ?string
    {
        return filled($value) ? trim($value) : null;
    }

    private static function syncSuggestion(string $model, ?string $name): void
    {
        if (blank($name)) {
            return;
        }

        $model::firstOrCreate(['name' => $name]);
    }

    private static function audit(Donation $donation, string $event, ?array $oldValues, ?array $newValues): void
    {
        DonationAuditLog::create([
            'donation_id' => $donation->exists ? $donation->id : null,
            'collection_id' => $donation->collection_id,
            'user_id' => Auth::id(),
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
