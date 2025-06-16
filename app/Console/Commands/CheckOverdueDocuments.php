<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckOverdueDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for documents that have been pending for 3+ days and send reminders to admins';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Checking for overdue documents...');

        try {
            NotificationService::createOverdueReminders();
            $this->info('Overdue document reminders have been sent successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error sending overdue reminders: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
