<?php

class CRM_Evalformdashboard_Trainer {
  public static function getEventEval($eventId) {
    $columns = [
      'algemene_tevredenheid',
      'ontvangst',
      'catering',
      'locatie',
      'cursusmateriaal',
      'interactie',
      'verwachting',
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
        civicrm_bemas_eval_trainer_event
      where
        event_id = %1
    ";
    $sqlParams = [
      1 => [$eventId, 'Integer']
    ];

    $eventEval = CRM_Core_DAO::executeQuery($sql, $sqlParams);
    $rows = [];
    while ($eventEval->fetch()) {
      $row = [];
      $row['algemene_tevredenheid'] = $eventEval->algemene_tevredenheid;
      $row['ontvangst'] = $eventEval->ontvangst;
      $row['catering'] = $eventEval->catering;
      $row['locatie'] = $eventEval->locatie;
      $row['cursusmateriaal'] = $eventEval->cursusmateriaal;
      $row['interactie'] = $eventEval->interactie;
      $row['verwachting'] = $eventEval->verwachting;
      $rows[] = $row;
    }

    return $rows;
  }
}
