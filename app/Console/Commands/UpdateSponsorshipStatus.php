<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Orphan;
use App\Models\Sponsorship;
use Illuminate\Console\Command;
use App\Notifications\SponsorshipEnded;
use App\Notifications\SponsorshipEndingSoon;

class UpdateSponsorshipStatus extends Command
{
    protected $signature = 'sponsorships:update-status';

    protected $description = 'تحديث حالة الكفالات بناءً على انتهاء المدة أو بلوغ اليتيم 18 سنة';

    protected int $daysLeft = 0;

    public function handle()
    {
        $today = Carbon::today();

        $sponsorships = Sponsorship::where('role', 'active')->get();

        foreach ($sponsorships as $sponsorship) {
            $endDate = Carbon::parse($sponsorship->start_date)
                        ->addMonths($sponsorship->duration)
                        ->startOfDay();

            $this->daysLeft = $today->diffInDays($endDate, false);

            $orphan = $sponsorship->orphan;

            // إذا بلغ اليتيم 18 سنة
            if ($orphan && $orphan->birth_date) {
                $age = Carbon::parse($orphan->birth_date)->age;
                if ($age >= 18) {
                    $sponsorship->update(['role' => 'Inactive']);
                    $orphan->update(['role' => 'archive']);
                    $this->notifyAboutSponsorship($sponsorship, 'ended');
                    continue;
                }
            }

            // إذا انتهت مدة الكفالة
            if ($today->greaterThanOrEqualTo($endDate)) {
                $sponsorship->update(['role' => 'Inactive']);

                if ($sponsorship->status === 'تم التسليم') {
                    if ($orphan->role !== 'certified') {
                        $orphan->update(['role' => 'certified']);
                        $this->info("✅ الكفالة رقم {$sponsorship->id} انتهت، وتم اعتماد اليتيم {$orphan->name}.");
                    }
                } elseif ($sponsorship->status === 'لم يتم التسليم') {
                    $this->info("⚠️ الكفالة رقم {$sponsorship->id} انتهت، لكن لم يتم تسليمها. تم إرسال إشعار.");
                    $this->notifyAboutSponsorship($sponsorship, 'not_delivered');
                }

                $this->notifyAboutSponsorship($sponsorship, 'finish');
            }

            // إشعار قرب الانتهاء
            elseif (in_array($this->daysLeft, [30, 14, 3])) {
                $this->info("🔔 الكفالة رقم {$sponsorship->id} ستنتهي بعد {$this->daysLeft} يومًا.");
                $this->notifyAboutSponsorship($sponsorship, 'soon');
            }
        }

        // تحديث حالة الأيتام الذين ليس لديهم كفالة نشطة
        Orphan::with('sponsorships')->each(function ($orphan) {
            $hasActive = $orphan->sponsorships()->where('role', 'active')->exists();
            if (!$hasActive && $orphan->role !== 'certified') {
                $orphan->update(['role' => 'certified']);
                $this->info("⏳ تم تحديث حالة اليتيم {$orphan->name} إلى الاعتماد.");
            }
        });

        $this->info('✅ تمت معالجة جميع الكفالات والأيتام بنجاح.');
    }

    protected function notifyAboutSponsorship(Sponsorship $sponsorship, string $type = 'soon'): void
    {
        $message = match ($type) {
            'ended' => "تم إنهاء الكفالة رقم {$sponsorship->id} لأن اليتيم {$sponsorship->orphan->name} بلغ 18 عامًا.",
            'finish' => "تم إنهاء الكفالة رقم {$sponsorship->id} لأن اليتيم {$sponsorship->orphan->name} انتهت مدة الكفالة.",
            'soon' => "🔔 الكفالة رقم {$sponsorship->id} لليتيم {$sponsorship->orphan->name} ستنتهي بعد {$this->daysLeft} يومًا.",
            'not_delivered' => "⚠️ الكفالة رقم {$sponsorship->id} لليتيم {$sponsorship->orphan->name} انتهت، لكنها لم تُسلَّم بعد. الرجاء تسليمها.",
        };

        $notification = match ($type) {
            'ended', 'finish' => new SponsorshipEnded($sponsorship, $message),
            'soon', 'not_delivered' => new SponsorshipEndingSoon($sponsorship, $message),
        };

        User::all()->each(function ($user) use ($notification) {
            $user->notify($notification);
        });

    }
}
