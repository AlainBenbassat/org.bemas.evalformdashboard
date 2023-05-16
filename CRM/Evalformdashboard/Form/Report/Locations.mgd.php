<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Evalformdashboard_Form_Report_Locations',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'Locations Evaluation Dashboard',
      'description' => 'Locations Evaluation Dashboard (org.bemas.evalformdashboard)',
      'class_name' => 'CRM_Evalformdashboard_Form_Report_Locations',
      'report_url' => 'org.bemas.evalformdashboard/locations',
      'component' => 'CiviEvent',
    ],
  ],
];
