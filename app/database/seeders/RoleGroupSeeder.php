<?php

namespace Database\Seeders;

use App\Models\RoleGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = [
            'EHS/North America',
            'India',
            'SEA',
            'EU'
        ];

        $processes_roles = [
            'Supplier' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'Supplier Audit' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'Risk Assessment' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'Change Control' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'Action Item' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'Root Cause Analysis' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'CAPA' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'SCAR' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'Supplier Site' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP'],
            'Deviation' => ['Initiator', 'HOD/Designee', 'Approver', 'Reviewer', 'View Only', 'FP']
        ];

        $start_from_id = 1; // Initialize your starting ID
        
        foreach ($sites as $site) {
            foreach ($processes_roles as $process => $roles) {
                foreach ($roles as $role) {
                    $group = new RoleGroup();
                    $group->id = $start_from_id;
                    $group->name = "$site-$process-$role";
                    $group->description = "$site-$process-$role";
                    $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
                    $group->save();
        
                    $start_from_id++;
                }
            }
        }

        // For seeding cft roles.
        
        $cft_roles = [
            "Production",
            "Warehouse",
            "Quality Control",
            "Quality Assurance",
            "Engineering",
            "Analytical Development Laboratory",
            "Process Development Laboratory / Kilo Lab",
            "Technology Transfer / Design",
            "Environment, Health & Safety",
            "Human Resource & Administration",
            "Information Technology",
            "Project Management"
        ];

        $processes = [
            'Supplier',
            'Supplier Audit',
            'Risk Assessment',
            'Change Control',
            'Action Item',
            'Root Cause Analysis',
            'CAPA',
            'SCAR',
            'Supplier Site',
            'Deviation'
        ];

        $incrementCount = $start_from_id;
        
        foreach ($processes as $process) {
            foreach ($sites as $site) {
                foreach ($cft_roles as $role) {
                    $group = new RoleGroup();
                    $group->id = $incrementCount++;
                    $group->name = "$site-$process-$role";
                    $group->description = "$site-$process-$role";
                    $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
                    $group->save();
                }
            }
        }

    }
}
