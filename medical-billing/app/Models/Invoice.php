<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'candidate_id',
        'test_type_id',
        'amount',
        'status',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_no)) {
                $invoice->invoice_no = self::generateInvoiceNumber();
            }
        });
    }

    /**
     * Generates sequential invoice numbers like INV-000001.
     * Uses the last invoice's numeric suffix rather than total count,
     * so numbering stays correct even if an invoice is ever deleted.
     */
    public static function generateInvoiceNumber(): string
    {
        $last = self::orderBy('id', 'desc')->first();

        $nextNumber = 1;
        if ($last && preg_match('/(\d+)$/', $last->invoice_no, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return 'INV-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function testType(): BelongsTo
    {
        return $this->belongsTo(TestType::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
