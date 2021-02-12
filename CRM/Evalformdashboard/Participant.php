<?php

class CRM_Evalformdashboard_Participant {
  public static function getEventEval($eventId) {
    $columns = [
      'algemene_tevredenheid',
      'invulling',
      'cursusmateriaal',
      'interactie',
      'kwaliteit',
      'bijgeleerd',
      'verwachting',
      'relevantie',
      'administratief_proces',
      'ontvangst',
      'catering',
      'locatie',
    ];

    $stats = '';
    foreach ($columns as $column) {
      $stats .= "round(sum($column) / count($column)) $column,";
    }

    $sql = "
      select
        $stats
        count(id) aantal_respondenten
      from
        civicrm_bemas_eval_participant_event
      where
        event_id = %1
    ";
    $sqlParams = [
      1 => [$eventId, 'Integer']
    ];

    $eventEval = CRM_Core_DAO::executeQuery($sql, $sqlParams);
    $eventEval->fetch();

    return $eventEval;
  }
}
