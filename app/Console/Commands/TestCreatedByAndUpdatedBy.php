<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestCreatedByAndUpdatedBy extends Command
{
    // The name and signature of the console command
    protected $signature = 'test:createdby-updatedby';

    // The console command description
    protected $description = 'Command to test the created_by and updated_by fields';

    // Execute the console command
    public function handle()
    {
        // Create a new record
        $model = new User();
        $model->email = 'testModelEvent@example.com';  // Set a value for required 'email' field (if applicable)
        $model->name = 'Test Record'; // Replace with actual attributes of your model
        $model->password = 'password';
        $model->save();

        $this->info('Record created successfully with created_by: system');

        // Update the same record to test updated_by
        $model->name = 'Updated Test Record';
        $model->save();

        $this->info('Record updated successfully with updated_by: system');
    }
}
