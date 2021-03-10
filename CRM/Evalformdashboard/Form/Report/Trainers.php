<?php
use CRM_Evalformdashboard_ExtensionUtil as E;

class CRM_Evalformdashboard_Form_Report_Trainers extends CRM_Report_Form {

  public function __construct() {
    $this->_columns = [
      'event_eval_dashboard' => [
        'fields' => $this->getTrainerDashboardFields(),
        'filters' => $this->getTrainerDashboardFilters(),
      ],
    ];

    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Trainer Evaluation Dashboard'));
    parent::preProcess();
  }

  private function getTrainerDashboardFields() {
    $fields = [
      'contact_id' => [
        'title' => E::ts('Contact Id'),
        'required' => TRUE,
        'dbAlias' => 'c.id',
      ],
      'trainer_name' => [
        'title' => E::ts('Name'),
        'required' => TRUE,
        'dbAlias' => "concat(c.first_name, ' ', c.last_name)",
      ],
      'expertise' => [
        'title' => 'Expertise',
        'required' => TRUE,
        'dbAlias' => 'round(sum(ept.expertise) / count(ept.expertise))',
      ],
      'didactische_vaardigheden' => [
        'title' => 'Didactische vaardigheden',
        'required' => TRUE,
        'dbAlias' => 'round(sum(ept.didactische_vaardigheden) / count(ept.didactische_vaardigheden))',
      ],
      'events' => [
        'title' => E::ts('Events'),
        'required' => TRUE,
        'dbAlias' => "group_concat(distinct concat(e.id, '~~~', e.title) SEPARATOR '###')",
      ],
    ];

    return $fields;
  }

  private function getTrainerDashboardFilters() {
    $today = date('Y-m-d');
    $todayLastYear = date('Y-m-d', strtotime('-1 years'));

    $filters = [
      'start_date' => [
        'title' => E::ts('Start Date'),
        'dbAlias' => 'e.start_date',
        'type' => CRM_Utils_Type::T_DATE,
        'operatorType' => CRM_Report_Form::OP_DATE,
        'default' => [
          'from' => $todayLastYear,
          'to' => $today,
        ]
      ],
      'event_type' => [
        'dbAlias' => 'e.event_type_id',
        'title' => E::ts('Event Types'),
        'type' => CRM_Utils_Type::T_INT,
        'operatorType' => CRM_Report_Form::OP_MULTISELECT,
        'options' => CRM_Event_PseudoConstant::eventType(),
      ],
      'is_active' => [
        'dbAlias' => 'e.is_active',
        'title' => E::ts('Active?'),
        'type' => CRM_Utils_Type::T_BOOLEAN,
        'default' => ['value' => 1],
      ]
    ];

    return $filters;
  }

  public function from() {
    $this->_from = "
      FROM
        civicrm_event e
      INNER JOIN
        civicrm_participant p ON p.event_id = e.id and (p.role_id like '%4%' or p.role_id like '%6%')
      INNER JOIN
        civicrm_contact c on c.id = p.contact_id
      LEFT OUTER JOIN
        civicrm_bemas_eval_participant_trainer ept ON ept.event_id = e.id and c.id = ept.contact_id
    ";
  }

  public function groupBy() {
    $this->_groupBy = "GROUP BY c.id, concat(c.first_name, ' ', c.last_name)";
  }

  public function orderBy() {
    $this->_orderBy = 'ORDER BY 2';
  }

  public function alterDisplay(&$rows) {
    foreach ($rows as $rowNum => $row) {
      $rows[$rowNum]['event_eval_dashboard_events'] = $this->getUrlToEvaluationDetails($row['event_eval_dashboard_events']);
    }
  }

  private function getUrlToEvaluationDetails($stringedEvents) {
    $tag = '<ul>';

    $events = explode('###', $stringedEvents);

    foreach ($events as $stringedEvent) {
      $event = explode('~~~', $stringedEvent);

      $url = CRM_Utils_System::url('civicrm/evalform-dashboard-event', 'reset=1&event_id=' . $event[0]);
      $tag .= '<li><a href="' . $url . '">' . $event[1] . '</a></li>';
    }

    return $tag . '</ul';
  }

}
