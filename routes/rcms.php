<?php

use App\Http\Controllers\rcms\ActionItemController;
use App\Http\Controllers\rcms\AuditeeController;
use App\Http\Controllers\rcms\CCController;
use App\Http\Controllers\rcms\DashboardController;
use App\Http\Controllers\rcms\EffectivenessCheckController;
use App\Http\Controllers\rcms\ExtensionController;
use App\Http\Controllers\rcms\InternalauditController;
use App\Http\Controllers\rcms\LabIncidentController;
use App\Http\Controllers\rcms\ObservationController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\rcms\AuditProgramController;
use App\Http\Controllers\rcms\CapaController;
use App\Http\Controllers\rcms\FormDivisionController;
use App\Http\Controllers\ExtensionNewController;
use App\Http\Controllers\rcms\ManagementReviewController;
use App\Http\Controllers\rcms\RootCauseController;
use App\Http\Controllers\RiskManagementController;
use App\Http\Controllers\rcms\DeviationController;
use App\Http\Controllers\rcms\SupplierController;
use App\Http\Controllers\rcms\SupplierSiteController;
use App\Http\Controllers\rcms\SCARController;
use App\Models\EffectivenessCheck;
use Illuminate\Support\Facades\Route;

// ============================================
//                   RCMS
//============================================

Route::group(['prefix' => 'rcms'], function () {
    Route::view('rcms', 'frontend.rcms.main-screen');
    Route::get('rcms_login', [UserLoginController::class, 'userlogin']);
    Route::view('rcms_dashboard', 'frontend.rcms.dashboard');
    Route::view('form-division', 'frontend.forms.form-division');
    Route::get('/logout', [UserLoginController::class, 'rcmslogout'])->name('rcms.logout');

    Route::middleware(['rcms'])->group(
        function () {
            Route::resource('CC', CCController::class);
            Route::resource('actionItem', ActionItemController::class);
            Route::post('action-stage-cancel/{id}', [ActionItemController::class, 'actionStageCancel']);
            Route::post('action-stage-more_info/{id}', [ActionItemController::class, 'actionStageMoreinfo']);
            Route::get('action-item-audittrialshow/{id}', [ActionItemController::class, 'actionItemAuditTrialShow'])->name('showActionItemAuditTrial');
            Route::get('action-item-audittrialdetails/{id}', [ActionItemController::class, 'actionItemAuditTrialDetails'])->name('showaudittrialactionItem');
            Route::get('actionitemSingleReport/{id}', [ActionItemController::class, 'singleReport'])->name('actionitemSingleReport');
            Route::get('actionitemAuditReport/{id}', [ActionItemController::class, 'auditReport'])->name('actionitemAuditReport');
            Route::get('effective-audit-trial-show/{id}', [EffectivenessCheckController::class, 'effectiveAuditTrialShow'])->name('show_effective_AuditTrial');
            Route::get('effective-audit-trial-details/{id}', [EffectivenessCheckController::class, 'effectiveAuditTrialDetails'])->name('show_audittrial_effective');
            Route::get('effectiveSingleReport/{id}', [EffectivenessCheckController::class, 'singleReport'])->name('effectiveSingleReport');
            Route::get('effectiveAuditReport/{id}', [EffectivenessCheckController::class, 'auditReport'])->name('effectiveAuditReport');

            // ------------------extension _child---------------------------
            Route::post('extension_child/{id}', [ExtensionController::class, 'extension_child'])->name('extension_child');
            Route::resource('extension', ExtensionController::class);
            Route::post('send-extension/{id}', [ExtensionController::class, 'stageChange']);
            Route::post('send-reject-extention/{id}', [ExtensionController::class, 'stagereject']);
            Route::post('send-cancel-extention/{id}', [ExtensionController::class, 'stagecancel']);
            Route::get('extension-audit-trial/{id}', [ExtensionController::class, 'extensionAuditTrial']);
            Route::get('extension-audit-trial-details/{id}', [ExtensionController::class, 'extensionAuditTrialDetails']);
            Route::get('extensionSingleReport/{id}', [ExtensionController::class, 'singleReport'])->name('extensionSingleReport');
            Route::get('extensionAuditReport/{id}', [ExtensionController::class, 'auditReport'])->name('extensionAuditReport');


            Route::post('send-At/{id}', [ActionItemController::class, 'stageChange']);
            Route::post('send-rejection-field/{id}', [CCController::class, 'stagereject']);
            Route::post('send-cft-field/{id}', [CCController::class, 'stageCFTnotReq']);

            Route::post('send-cancel/{id}', [CCController::class, 'stagecancel']);
            Route::post('send-cc/{id}', [CCController::class, 'stageChange']);
            Route::post('child/{id}', [CCController::class, 'child']);
            Route::get('qms-dashboard', [DashboardController::class, 'index']);
            Route::get('qms-dashboard/{id}/{process}', [DashboardController::class, 'dashboard_child']);
            Route::get('qms-dashboard_new/{id}/{process}', [DashboardController::class, 'dashboard_child_new']);
            Route::get('audit-trial/{id}', [CCController::class, 'auditTrial']);
            Route::get('audit-detail/{id}', [CCController::class, 'auditDetails']);
            Route::get('summary/{id}', [CCController::class, 'summery_pdf']);
            Route::get('audit/{id}', [CCController::class, 'audit_pdf']);

            Route::get('ccView/{id}/{type}', [DashboardController::class, 'ccView'])->name('ccView');
            Route::view('summary_pdf', 'frontend.change-control.summary_pdf');
            Route::view('audit_trial_pdf', 'frontend.change-control.audit_trial_pdf');
            Route::view('change_control_single_pdf', 'frontend.change-control.change_control_single_pdf');
            Route::get('change_control_family_pdf', [CCController::class, 'parent_child']);

            Route::get('change_control_single_pdf/{id}', [CCController::class, 'single_pdf']);
            Route::get('eCheck/{id}', [CCController::class, 'eCheck']);
            Route::resource('effectiveness', EffectivenessCheckController::class);
            Route::post('send-effectiveness/{id}', [EffectivenessCheckController::class, 'stageChange']);
            Route::post('effectiveness-reject/{id}', [EffectivenessCheckController::class, 'reject']);
            Route::post('cancel/{id}',[EffectivenessCheckController::class,'cancel'])->name('moreinfo_effectiveness');
            Route::view('helpdesk-personnel', 'frontend.rcms.helpdesk-personnel');
            Route::view('send-notification', 'frontend.rcms.send-notification');
            Route::get('new-change-control', [CCController::class, 'changecontrol']);

            //----------------------------------------------By Pankaj-----------------------

            Route::post('audit', [InternalauditController::class, 'create'])->name('createInternalAudit');
            Route::get('internalAuditShow/{id}', [InternalauditController::class, 'internalAuditShow'])->name('showInternalAudit');
            Route::post('update/{id}', [InternalauditController::class, 'update'])->name('updateInternalAudit');
            Route::post('InternalAuditStateChange/{id}', [InternalauditController::class, 'InternalAuditStateChange'])->name('AuditStateChange');
            Route::get('InternalAuditTrialShow/{id}', [InternalauditController::class, 'InternalAuditTrialShow'])->name('ShowInternalAuditTrial');
            Route::get('InternalAuditTrialDetails/{id}', [InternalauditController::class, 'InternalAuditTrialDetails'])->name('showaudittrialinternalaudit');

            //-------------------------

            Route::post('labcreate', [LabIncidentController::class, 'create'])->name('labIncidentCreate');
            Route::get('LabIncidentShow/{id}', [LabIncidentController::class, 'LabIncidentShow'])->name('ShowLabIncident');
            Route::post('LabIncidentStateChange/{id}', [LabIncidentController::class, 'LabIncidentStateChange'])->name('StageChangeLabIncident');
            Route::post('RejectStateChangeEsign/{id}', [LabIncidentController::class, 'RejectStateChange'])->name('RejectStateChange');
            Route::post('updateLabIncident/{id}', [LabIncidentController::class, 'updateLabIncident'])->name('LabIncidentUpdate');
            Route::post('LabIncidentCancel/{id}', [LabIncidentController::class, 'LabIncidentCancel'])->name('LabIncidentCancel');
            Route::post('LabIncidentChildCapa/{id}', [LabIncidentController::class, 'lab_incident_capa_child'])->name('lab_incident_capa_child');
            Route::post('LabIncidentChildRoot/{id}', [LabIncidentController::class, 'lab_incident_root_child'])->name('lab_incident_root_child');
            Route::get('LabIncidentAuditTrial/{id}', [LabIncidentController::class, 'LabIncidentAuditTrial'])->name('audittrialLabincident');
            Route::get('auditDetailsLabIncident/{id}', [LabIncidentController::class, 'auditDetailsLabIncident'])->name('LabIncidentauditDetails');
            Route::post('root_cause_analysis/{id}', [LabIncidentController::class, 'root_cause_analysis'])->name('Child_root_cause_analysis');
            Route::get('LabIncidentSingleReport/{id}', [LabIncidentController::class, 'singleReport'])->name('LabIncidentSingleReport');
            Route::get('LabIncidentAuditReport/{id}', [LabIncidentController::class, 'auditReport'])->name('LabIncidentAuditReport');
            //------------------------------------

            
            Route::post('create', [AuditProgramController::class, 'create'])->name('createAuditProgram');
            Route::get('AuditProgramShow/{id}', [AuditProgramController::class, 'AuditProgramShow'])->name('ShowAuditProgram');
            Route::post('AuditStateChange/{id}', [AuditProgramController::class, 'AuditStateChange'])->name('StateChangeAuditProgram');
            Route::post('AuditRejectStateChange/{id}', [AuditProgramController::class, 'AuditRejectStateChange'])->name('AuditProgramStateRecject');
            Route::post('UpdateAuditProgram/{id}', [AuditProgramController::class, 'UpdateAuditProgram'])->name('AuditProgramUpdate');
            Route::get('AuditProgramTrialShow/{id}', [AuditProgramController::class, 'AuditProgramTrialShow'])->name('showAuditProgramTrial');
            Route::get('auditProgramDetails/{id}', [AuditProgramController::class, 'auditProgramDetails'])->name('auditProgramAuditTrialDetails');
            Route::post('child_audit_program/{id}', [AuditProgramController::class, 'child_audit_program'])->name('auditProgramChild');
            Route::post('AuditProgramCancel/{id}', [AuditProgramController::class, 'AuditProgramCancel'])->name('AuditProgramCancel');
            Route::get('auditProgramSingleReport/{id}', [AuditProgramController::class, 'singleReport'])->name('auditProgramSingleReport');
            Route::get('auditProgramAuditReport/{id}', [AuditProgramController::class, 'auditReport'])->name('auditProgramAuditReport');




            Route::get('observationshow/{id}', [ObservationController::class, 'observationshow'])->name('showobservation');
            Route::post('observationstore', [ObservationController::class, 'observationstore'])->name('observationstore');
            Route::post('observationupdate/{id}', [ObservationController::class, 'observationupdate'])->name('observationupdate');
            Route::post('observation_send_stage/{id}', [ObservationController::class, 'observation_send_stage'])->name('observation_change_stage');
            Route::post('RejectStateChange/{id}', [ObservationController::class, 'RejectStateChange'])->name('RejectStateChangeObservation');
            Route::post('observation_child/{id}', [ObservationController::class, 'observation_child'])->name('observationchild');
            Route::post('boostStage/{id}', [ObservationController::class, 'boostStage'])->name('updatestageobservation');

            Route::get('ShowObservationAuditTrial/{id}', [ObservationController::class, 'ObservationAuditTrialShow'])->name('ShowObservationAuditTrial');
            Route::get('showaudittrialobservation/{id}', [ObservationController::class, 'ObservationAuditTrialDetails'])->name('showaudittrialobservation');
            Route::get('ObservationSingleReport/{id}', [ObservationController::class, 'ObservationSingleReportshow'])->name('ObservationSingleReport');

            /*********** Supplier Routes ************/
            Route::get('supplier', [SupplierController::class, 'index']);
            Route::post('supplier-store', [SupplierController::class, 'store'])->name('supplier-store');
            Route::get('supplier-show/{id}', [SupplierController::class, 'show']);
            Route::post('supplier-update/{id}', [SupplierController::class, 'update'])->name('supplier-update');
            Route::get('supplier-single-report/show/{id}', [SupplierController::class, 'singleReportShow'])->name('supplier.single.report.show');
            Route::get('supplier-single-report/{id}', [SupplierController::class, 'singleReport'])->name('supplier.single.report');
            Route::get('supplier-audit-trail/{id}', [SupplierController::class, 'auditTrail']);
            Route::get('supplier-audit-trail-pdf/{id}', [SupplierController::class, 'auditTrailPdf']);
            Route::post('supplier-send-stage/{id}', [SupplierController::class, 'supplierSendStage'])->name('supplier-send-stage');
            Route::post('sendTo-supplier-approved/{id}', [SupplierController::class, 'sendToSupplierApproved'])->name('sendTo-supplier-approved');
            Route::post('supplier-close-cancelled/{id}', [SupplierController::class, 'cancelDocument'])->name('supplier-close-cancelled');
            Route::post('supplier-approved-to-obselete/{id}', [SupplierController::class, 'supplierApprovedToObselete'])->name('supplier-approved-to-obselete');
            Route::post('sendToPendingSupplierAudit/{id}', [SupplierController::class, 'sendToPendingSupplierAudit'])->name('sendToPendingSupplierAudit');
            Route::post('supplier_child/{id}', [SupplierController::class, 'supplier_child'])->name('supplier_child_1');            
            Route::post('store_audit_review/{id}', [SupplierController::class, 'store_audit_review'])->name('store_audit_review');
            Route::post('approvedBy-contract-giver/{id}', [SupplierController::class, 'approvedByContractGiver'])->name('approvedBy-contract-giver');
            Route::post('link-manufacturer/{id}', [SupplierController::class, 'linkManufacturerToApprovedManufacturer'])->name('link-manufacturer');

            Route::post('supplier-reject-stage/{id}', [SupplierController::class, 'supplierStageReject'])->name('supplier-reject-stage');
            Route::post('sendTo-pendig-CQA/{id}', [SupplierController::class, 'sendToPendingCQAReview'])->name('sendTo-pendig-CQA');
            Route::post('manufacturer-reject/{id}', [SupplierController::class, 'manufacturerRejected'])->name('manufacturer-reject');
            Route::post('risk-rating-observed-low/{id}', [SupplierController::class, 'sendToApprovedManufacturerFromPendingManufacturer'])->name('risk-rating-observed-low');
            /*********** Supplier Site Routes ************/
            Route::get('supplier-site', [SupplierSiteController::class, 'index']);
            Route::post('supplier-site-store', [SupplierSiteController::class, 'store'])->name('supplier-site-store');
            Route::get('supplier-site-show/{id}', [SupplierSiteController::class, 'show']);
            Route::post('supplier-site-update/{id}', [SupplierSiteController::class, 'update'])->name('supplier-site-update');
            Route::get('supplier-site-single-report/{id}', [SupplierSiteController::class, 'singleReport']);
            Route::get('supplier-site-audit-trail/{id}', [SupplierSiteController::class, 'auditTrail']);
            Route::get('supplier-site-audit-trail-pdf/{id}', [SupplierSiteController::class, 'auditTrailPdf']);
            Route::post('supplier-site-send-stage/{id}', [SupplierSiteController::class, 'supplierSendStage'])->name('supplier-site-send-stage');
            Route::post('sendTo-supplier-site-approved/{id}', [SupplierSiteController::class, 'sendToSupplierApproved'])->name('sendTo-supplier-site-approved');
            Route::post('supplier-site-close-cancelled/{id}', [SupplierSiteController::class, 'cancelDocument'])->name('supplier-site-close-cancelled');
            Route::post('supplier-site-approved-to-obselete/{id}', [SupplierSiteController::class, 'supplierApprovedToObselete'])->name('supplier-site-approved-to-obselete');
            Route::post('sendToPendingSupplierSiteAudit/{id}', [SupplierSiteController::class, 'sendToPendingSupplierAudit'])->name('sendToPendingSupplierSiteAudit');
            Route::post('suppliersite_child/{id}', [SupplierSiteController::class, 'supplier_child'])->name('suppliersite_child');

            /*********** SCAR Routes ************/
            Route::get('scar', [SCARController::class, 'index']);
            Route::post('scar-store', [SCARController::class, 'store'])->name('scar-store');
            Route::get('scar-show/{id}', [SCARController::class, 'show']);
            Route::post('scar-update/{id}', [SCARController::class, 'update'])->name('scar-update');
            Route::get('scar-single-report/{id}', [SCARController::class, 'singleReport']);
            Route::get('scar-audit-trail/{id}', [SCARController::class, 'auditTrail']);
            Route::get('scar-audit-trail-pdf/{id}', [SCARController::class, 'auditTrailPdf']);
            Route::post('scar-send-stage/{id}', [SCARController::class, 'sendStage'])->name('scar-send-stage');
            Route::post('scar-close-cancelled/{id}', [SCARController::class, 'sendToCancel'])->name('scar-close-cancelled');
            Route::post('scar-reject-stage/{id}', [SCARController::class, 'rejectStage'])->name('scar-reject-stage');

        
            //----------------------------------------------By PRIYA SHRIVASTAVA------------------
            Route::post('formDivision', [FormDivisionController::class, 'formDivision'])->name('formDivision');
            Route::get('ExternalAuditSingleReport/{id}', [AuditeeController::class, 'singleReport'])->name('ExternalAuditSingleReport');
            Route::get('ExternalAuditTrialReport/{id}', [AuditeeController::class, 'auditReport'])->name('ExternalAuditTrialReport');
            Route::get('capaSingleReport/{id}', [CapaController::class, 'singleReport'])->name('capaSingleReport');
            Route::get('capaAuditReport/{id}', [CapaController::class, 'auditReport'])->name('capaAuditReport');
            Route::get('riskSingleReport/{id}', [RiskManagementController::class, 'singleReport'])->name('riskSingleReport');
            Route::get('riskAuditReport/{id}', [RiskManagementController::class, 'auditReport'])->name('riskAuditReport');
            Route::get('rootSingleReport/{id}', [RootCauseController::class, 'singleReport'])->name('rootSingleReport');
            Route::get('rootAuditReport/{id}', [RootCauseController::class, 'auditReport'])->name('rootAuditReport');
            Route::get('managementReview/{id}', [ManagementReviewController::class, 'managementReport'])->name('managementReport');
            Route::get('managementReviewReport/{id}', [ManagementReviewController::class, 'managementReviewReport'])->name('managementReviewReport');
            Route::post('child_management_Review/{id}', [ManagementReviewController::class, 'child_management_Review'])->name('childmanagementReview');
            Route::get('internalSingleReport/{id}', [InternalauditController::class, 'singleReport'])->name('internalSingleReport');
            Route::get('internalauditReport/{id}', [InternalauditController::class, 'auditReport'])->name('internalauditReport');

            //Route::resource('deviation', DeviationController::class);
            Route::get('devshow/{id}', [DeviationController::class, 'devshow'])->name('devshow');
            Route::get('auditReport/{id}', [DeviationController::class, 'auditReport'])->name('auditReport');
            Route::post('deviation/stage/{id}', [DeviationController::class, 'deviation_send_stage'])->name('deviation_send_stage');
            Route::post('deviation/cancel/{id}', [DeviationController::class, 'deviationCancel'])->name('deviationCancel');
            // Route::post('deviation/cftnotrequired/{id}', [DeviationController::class, 'deviationIsCFTRequired'])->name('deviationIsCFTRequired');
            Route::post('deviation/reject/{id}', [DeviationController::class, 'deviation_reject'])->name('deviation_reject');
            Route::post('deviation/check/{id}', [DeviationController::class, 'check'])->name('check');
            Route::post('deviation/check2/{id}', [DeviationController::class, 'check2'])->name('check2');
            Route::post('deviation/check3/{id}', [DeviationController::class, 'check3'])->name('check3');
            Route::post('deviation/cftnotreqired/{id}', [DeviationController::class, 'cftnotreqired'])->name('cftnotreqired');
            Route::post('deviation/checkcft/{id}', [DeviationController::class, 'checkcft'])->name('checkcft');
            Route::post('deviation/Qa/{id}', [DeviationController::class, 'deviation_qa_more_info'])->name('deviation_qa_more_info');
            Route::post('deviationstore', [DeviationController::class, 'store'])->name('deviationstore');
            Route::post('deviationupdate/{id}', [DeviationController::class, 'update'])->name('deviationupdate');
             Route::get('deviation', [DeviationController::class, 'deviation']);
             Route::get('deviationSingleReport/{id}', [DeviationController::class, 'singleReport'])->name('deviationSingleReport');
             Route::get('deviationparentchildReport/{id}', [DeviationController::class, 'parentchildReport'])->name('deviationparentchildReport');
             Route::post('launch-extension-qrm/{id}', [DeviationController::class, 'launchExtensionQrm'])->name('launch-extension-qrm');             
            Route::post('launch-extension-investigation/{id}', [DeviationController::class, 'launchExtensionInvestigation'])->name('launch-extension-investigation');
            Route::post('launch-extension-deviation/{id}', [DeviationController::class, 'launchExtensionDeviation'])->name('launch-extension-deviation');
            Route::post('launch-extension-capa/{id}', [DeviationController::class, 'launchExtensionCapa'])->name('launch-extension-capa');
            Route::post('deviation/pending_initiator_update/{id}', [DeviationController::class, 'pending_initiator_update'])->name('pending_initiator_update');
            Route::get('devAuditreport/{id}', [DeviationController::class, 'devAuditreport'])->name('devAuditreport');

            //===============================extension new --------------
            Route::get('singleReportNew/{id}', [ExtensionNewController::class, 'singleReport'])->name('singleReportNew');
            Route::get('audit_trailNew/{id}', [ExtensionNewController::class, 'extensionNewAuditTrail']);
            Route::get('auditReportext/{id}', [ExtensionNewController::class, 'auditReportext'])->name('auditReportext');
             

        }
    );
});
