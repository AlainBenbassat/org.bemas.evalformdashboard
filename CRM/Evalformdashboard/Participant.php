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

  public static function getTrainerEval($eventId) {
    $columns = [
      'expertise',
      'didactische_vaardigheden',
    ];

    $stats = '';
    foreach ($columns as $column) {
      $stats .= "round(sum(e.$column) / count(e.$column)) $column,";
    }

    $sql = "
      select
        concat(c.first_name, ' ', c.last_name) trainer_name,
        $stats
        count(e.id) aantal_respondenten
      from
        civicrm_bemas_eval_participant_trainer e
      inner join
        civicrm_contact c on c.id = e.contact_id
      where
        e.event_id = %1
      group by
        e.contact_id
        , concat(c.first_name, ' ', c.last_name)
      order by
        1
    ";
    $sqlParams = [
      1 => [$eventId, 'Integer']
    ];

    $eventEval = CRM_Core_DAO::executeQuery($sql, $sqlParams);
    $rows = [];
    while ($eventEval->fetch()) {
      $row = [];
      $row['trainer_name'] = $eventEval->trainer_name;
      $row['expertise'] = $eventEval->expertise;
      $row['didactische_vaardigheden'] = $eventEval->didactische_vaardigheden;
      $rows[] = $row;
    }

    return $rows;
  }
}
