<?php

class CRM_Evalformdashboard_Event {
  public static function get($eventId) {
    $sql = "
      select
        e.title,
        e.start_date,
        e1.thema_132 theme_ids,
        e2.aantal_uren_157 num_hours
      from
        civicrm_event e
      left outer join
        civicrm_value_opleiding_lesduur_23 e1 on e1.entity_id = e.id
      left outer join
        civicrm_value_activiteit_status_25 e2 on e2.entity_id = e.id
      where
        e.id = %1
    ";
    $sqlParams = [
      1 => [$eventId, 'Integer']
    ];
    $event = CRM_Core_DAO::executeQuery($sql, $sqlParams);
    $event->fetch();

    $event->themes = self::getThemes($event->theme_ids);
    $event->language = self::getLanguage($event->title);
    $event->num_participants = self::getNumParticipants($eventId);
    $event->num_evaluations = self::getNumEvaluations($eventId);
    $event->response_rate = self::calcResponseRate($event->num_participants, $event->num_evaluations);
    $event->num_evaluations = self::getNumEvaluations($eventId);
    $event->response_participant_link = self::getResponseLink($eventId, 'participant');
    $event->response_trainer_link = self::getResponseLink($eventId, 'trainer');

    return $event;
  }

  public static function getThemes($themeIds) {
    $themes = '';

    $themeIdList = implode(', ', CRM_Utils_Array::explodePadded($themeIds));
    if ($themeIdList) {
      $sql = "
        select
          group_concat(label separator ', ')
        from
          civicrm_option_value
        where
          option_group_id = 141
        and
          value in ($themeIdList)
      ";
      $themes = CRM_Core_DAO::singleValueQuery($sql);
    }

    return $themes;
  }

  public static function getLanguage($title) {
    $language = '';
    $n = strpos($title, ' - ');
    if ($n > 0) {
      $eventCode = substr($title, 0, $n);
      $lastLetter = substr($eventCode, -1);

      if ($lastLetter == 'V') {
        $language = 'Nederlands';
      }
      elseif ($lastLetter == 'W') {
        $language =  'Français';
      }
      elseif ($lastLetter == 'N') {
        $language =  'English';
      }
    }

    return $language;
  }

  public static function getNumParticipants($eventId) {
    $sql = "select count(*) from civicrm_participant where event_id = $eventId and status_id in (1, 2, 16) and role_id = '1'";
    return CRM_Core_DAO::singleValueQuery($sql);
  }

  public static function getNumEvaluations($eventId) {
    $sql = "select count(*) from civicrm_bemas_eval_participant_event where event_id = $eventId";
    return CRM_Core_DAO::singleValueQuery($sql);
  }

  private static function calcResponseRate($numParticipants, $numEvaluations) {
    if ($numParticipants > 0) {
      $responseRate = round($numEvaluations / $numParticipants * 100) . '%';
    }
    else {
      $responseRate = '';
    }

    return $responseRate;
  }

  private static function getResponseLink($eventId, $type) {
    $link = '';

    $sql = "select ifnull(max(nid), 0) from civicrm_bemas_eval_{$type}_event where event_id = $eventId";
    $nid = CRM_Core_DAO::singleValueQuery($sql);
    if ($nid) {
      $link = CRM_Utils_System::baseURL() . "node/$nid/webform-results/table";
      $link = '<a href="' . $link . '">' . $link . '</a>';
    }

    return $link;
  }

}
