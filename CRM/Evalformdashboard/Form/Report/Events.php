<?php
use CRM_Evalformdashboard_ExtensionUtil as E;

class CRM_Evalformdashboard_Form_Report_Events extends CRM_Report_Form {

  public function __construct() {
    $this->_columns = [
      'event_eval_dashboard' => [
        'fields' => $this->getEventDashboardFields(),
        'filters' => $this->getEventDashboardFilters(),
      ],
    ];

    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Event Evaluation Dashboard'));
    parent::preProcess();
  }

  private function getEventDashboardFields() {
    $fields = [
      'event_id' => [
        'title' => E::ts('Event Id'),
        'required' => TRUE,
        'dbAlias' => 'e.id',
      ],
      'start_date' => [
        'title' => E::ts('Start Date'),
        'required' => TRUE,
        'dbAlias' => 'e.start_date',
      ],
      'event_type' => [
        'title' => E::ts('Event Type'),
        'required' => TRUE,
        'dbAlias' => 'et.label',
      ],
      'title' => [
        'title' => E::ts('Event'),
        'required' => TRUE,
        'dbAlias' => 'e.title',
      ],
      'duration' => [
        'title' => 'Aantal lesuren',
        'required' => TRUE,
        'dbAlias' => 'ed.aantal_uren_157',
      ],
      'language' => [
        'title' => E::ts('Language'),
        'required' => TRUE,
        'dbAlias' => 'NULL',
      ],
      'num_participants' => [
        'title' => '#Deelnemers',
        'required' => TRUE,
        'dbAlias' => 'NULL',
      ],
      'num_evaluations' => [
        'title' => '#Evaluaties',
        'required' => TRUE,
        'dbAlias' => 'NULL',
      ],
      'algemene_tevredenheid' => [
        'title' =>'Algemene tevredenheid',
        'required' => TRUE,
        'dbAlias' => 'round(sum(epe.algemene_tevredenheid) / count(epe.algemene_tevredenheid))',
      ],
      'invulling' => [
        'title' => 'Invulling',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'cursusmateriaal' => [
        'title' => 'Cursusmateriaal',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'interactie' => [
        'title' => 'Interactie',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'kwaliteit' => [
        'title' => 'Kwaliteit',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'bijgeleerd' => [
        'title' => 'Bijgeleerd',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'verwachting' => [
        'title' => 'Verwachting',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'relevantie' => [
        'title' => 'Relevantie',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'administratief_proces' => [
        'title' => 'Administratief proces',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'ontvangst' => [
        'title' => 'Ontvangst',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'catering' => [
        'title' => 'Catering',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
      'locatie' => [
        'title' => 'Locatie',
        'required' => FALSE,
        'dbAlias' => 'NULL',
      ],
    ];

    return $fields;
  }

  private function getEventDashboardFilters() {
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
        civicrm_option_value et ON et.value = e.event_type_id and et.option_group_id = 14
      LEFT OUTER JOIN
        civicrm_value_activiteit_status_25 ed ON ed.entity_id = e.id
      LEFT OUTER JOIN
        civicrm_bemas_eval_participant_event epe ON epe.event_id = e.id
    ";
  }

  public function groupBy() {
    $this->_groupBy = 'GROUP BY e.id, e.start_date, et.label, e.title, ed.aantal_uren_157';
  }

  public function orderBy() {
    $this->_orderBy = 'ORDER BY e.start_date DESC, e.title';
  }

  public function alterDisplay(&$rows) {
    $optionalColumns = [
      'invulling', 'cursusmateriaal', 'interactie', 'kwaliteit', 'bijgeleerd', 'verwachting', 'relevantie', 'administratief_proces', 'ontvangst', 'catering', 'locatie'
    ];

    foreach ($rows as $rowNum => $row) {
      $participantEventEval = CRM_Evalformdashboard_Participant::getEventEval($row['event_eval_dashboard_event_id']);

      if ($row['event_eval_dashboard_algemene_tevredenheid']) {
        $rows[$rowNum]['event_eval_dashboard_algemene_tevredenheid'] = $this->getUrlToEvaluationDetails($row['event_eval_dashboard_algemene_tevredenheid'], $row['event_eval_dashboard_event_id']);
      }

      $rows[$rowNum]['event_eval_dashboard_num_participants'] = CRM_Evalformdashboard_Event::getNumParticipants($row['event_eval_dashboard_event_id']);
      $rows[$rowNum]['event_eval_dashboard_num_evaluations'] = CRM_Evalformdashboard_Event::getNumEvaluations($row['event_eval_dashboard_event_id']);
      $rows[$rowNum]['event_eval_dashboard_language'] = CRM_Evalformdashboard_Event::getLanguage($row['event_eval_dashboard_title']);

      foreach ($optionalColumns as $optionalColumn) {
        if (array_key_exists("event_eval_dashboard_$optionalColumn", $row)) {
          $rows[$rowNum]["event_eval_dashboard_$optionalColumn"] = $participantEventEval->$optionalColumn;
        }
      }
    }
  }

  private function getUrlToEvaluationDetails($value, $eventId) {
    $url = CRM_Utils_System::url('civicrm/evalform-dashboard-event', 'reset=1&event_id=' . $eventId);
    $tag = '<a href="' . $url . '">' . $value . '</a>';
    return $tag;
  }

}
