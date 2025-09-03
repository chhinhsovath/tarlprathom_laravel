<?php

namespace App\Jobs;

use App\Exports\MentoringVisitsExport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Notifications\ExportReadyNotification;
use Illuminate\Support\Facades\URL;

class ExportMentoringVisitsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $filters;
    protected $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, array $filters = [])
    {
        $this->user = $user;
        $this->filters = $filters;
        $this->fileName = 'mentoring_visits_' . date('Y-m-d_H-i-s') . '_' . $user->id . '.xlsx';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Create request object with filters for export
        $request = new Request($this->filters);
        
        // Create the export
        $export = new MentoringVisitsExport($request);
        
        // Store the file
        Excel::store($export, 'exports/' . $this->fileName, 'public');
        
        // Generate download URL
        $downloadUrl = URL::temporarySignedRoute(
            'exports.download',
            now()->addDays(7),
            ['file' => $this->fileName]
        );
        
        // Send notification to user
        $this->user->notify(new ExportReadyNotification(
            $this->fileName,
            $downloadUrl,
            'Mentoring Visits'
        ));
        
        // Log success
        \Log::info('Export completed and notification sent', [
            'user_id' => $this->user->id,
            'file' => $this->fileName
        ]);
        
        // Clean up old exports
        $this->cleanOldExports();
    }

    /**
     * Clean up old export files (older than 7 days)
     */
    private function cleanOldExports(): void
    {
        $files = Storage::disk('public')->files('exports');
        $now = now();
        
        foreach ($files as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);
            if ($now->diffInDays($lastModified) > 7) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
