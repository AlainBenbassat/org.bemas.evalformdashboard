<?php
use CRM_Evalformdashboard_ExtensionUtil as E;

class CRM_Evalformdashboard_Form_Report_Locations extends CRM_Report_Form {

  public function __construct() {
    $this->_columns = [
      'event_eval_dashboard' => [
        'fields' => $this->getLocationsDashboardFields(),
        'filters' => $this->getLocationsDashboardFilters(),
      ],
    ];

    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Locations Evaluation Dashboard'));
    parent::preProcess();
  }

  private function getLocationsDashboardFields() {
    $fields = [
      'contact_id' => [
        'title' => E::ts('Contact Id'),
        'required' => TRUE,
        'dbAlias' => 'c.id',
      ],
      'location_name' => [
        'title' => E::ts('Location'),
        'required' => TRUE,
        'dbAlias' => "c.organization_name",
      ],
      'postal_code' => [
        'title' => E::ts('Postal Code'),
        'required' => TRUE,
        'dbAlias' => "a.postal_code",
      ],
      'annulatievoorwaarden' => [
        'title' => 'Annulatievoorwaarden',
        'required' => TRUE,
        'dbAlias' => "cli.annulatievoorwaarden_198",
      ],
      'contactpersoon' => [
        'title' => 'Contactpersoon',
        'required' => TRUE,
        'dbAlias' => "concat(cp.first_name, ' ', cp.last_name)",
      ],
      'ontvangst' => [
        'title' => 'Ontvangst',
        'required' => TRUE,
        'dbAlias' => 'round(sum(epe.ontvangst) / count(epe.ontvangst))',
      ],
      'catering' => [
        'title' => 'Catering',
        'required' => TRUE,
        'dbAlias' => 'round(sum(epe.catering) / count(epe.catering))',
      ],
      'locatie' => [
        'title' => 'Locatie',
        'required' => TRUE,
        'dbAlias' => 'round(sum(epe.locatie) / count(epe.locatie))',
      ],
      'events' => [
        'title' => E::ts('Events'),
        'required' => TRUE,
        'dbAlias' => "group_concat(distinct concat(e.id, '~~~', e.title) SEPARATOR '###')",
      ],
    ];

    return $fields;
  }

  private function getLocationsDashboardFilters() {
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
        civicrm_value_opleiding_lesduur_23 l ON l.entity_id = e.id
      INNER JOIN
        civicrm_contact c on c.id = l.eventlocatie_195
      LEFT OUTER JOIN
        civicrm_address a on a.contact_id = c.id and a.is_primary = 1
      LEFT OUTER JOIN
        civicrm_value_locatie_infor_46 cli on cli.entity_id = c.id
      LEFT OUTER JOIN
        civicrm_contact cp on cp.id = cli.vaste_contactpersoon_199
      LEFT OUTER JOIN
        civicrm_bemas_eval_participant_event epe ON epe.event_id = e.id
    ";
  }

  public function groupBy() {
    $this->_groupBy = "GROUP BY c.id";
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
