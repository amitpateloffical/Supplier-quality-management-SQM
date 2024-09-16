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
            'Supplier' => ['Purchase Department','Initiator','CQA','F&D/MS&T', 'View Only', 'FP'],
            'Supplier Site' => ['Initiator', 'Supplier Auditor', 'Supplier Contact Department', 'View Only', 'FP'],
            'Supplier Audit' => ['Audit Manager', 'Supplier Auditor', 'Auditee', 'Business Rule Engine', 'View Only', 'FP'],
            'Risk Assessment' => ['Initiator', 'HOD/Designee', 'Work Group','QA', 'View Only', 'FP'],
            'Change Control' => ['Initiator', 'HOD/Designee', 'CFT/SME','View Only', 'FP'],
            'CAPA' => ['Initiator', 'HOD/Designee', 'QA Head Designee','QA', 'View Only', 'FP'],
            'Observation' => ['Auditors', 'Business Rule Engine', 'Auditees','Quality', 'View Only', 'FP'],
            'Deviation' => ['Initiator', 'HOD/Designee', 'QA', 'CFT/SME' ,'QA Head Designee','QA Reviewer', 'View Only', 'FP'],
            'Action Item' => ['Initiator', 'Action Owner','View Only', 'FP'],
            'Extension' => ['Initiator', 'HOD/Designee', 'QA Approver', 'View Only', 'FP'],
            'Effectiveness Check' => ['Initiator', 'Supervisor', 'QA', 'View Only', 'FP'],
            'Root Cause Analysis' => ['Initiator', 'QA', 'View Only', 'FP'],
            'SCAR' => ['SCAR Initiator', 'Vendors', 'SCAR Initiator', 'View Only', 'FP'],
            'OOS/OOT' => ['Initiatoe', 'QA', 'CQA', 'View Only', 'FP'],
            'OOS Micro' => ['OOS Initiator', 'CQA', 'View Only', 'FP'],
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
            'Deviation',
            'Extension',
            'Observation',
            'Effectiveness Check',
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
