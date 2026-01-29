<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMarbotDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marbot:check-deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reject marbot applications that have exceeded their improvement deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = 0;
        $today = now()->startOfDay();

        $this->info('Checking for deadlines before: '.$today->toDateString());

        // Find marbots with status 'perbaikan' and deadline < today
        $expiredMarbots = \App\Models\Marbot::where('status', 'perbaikan')
            ->whereDate('deadline_perbaikan', '<', $today)
            ->get();

        foreach ($expiredMarbots as $marbot) {
            $marbot->status = 'ditolak';
            $oldNote = $marbot->catatan;
            $deadline = $marbot->deadline_perbaikan ? $marbot->deadline_perbaikan->translatedFormat('d F Y') : '-';

            $marbot->catatan = $oldNote."\n\n[SYSTEM]: Permohonan ditolak otomatis karena melewati batas waktu perbaikan yang ditentukan ($deadline).";
            $marbot->save();

            $this->info("Rejected Marbot: {$marbot->nama_lengkap} (Deadline: $deadline)");
            $count++;
        }

        $this->info("Process completed. Total rejected: {$count}");
    }
}
