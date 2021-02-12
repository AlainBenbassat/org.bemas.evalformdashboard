<?php

class CRM_Evalformdashboard_Event {
  public static function get($eventId) {
    $sql = "
      select
        e.title,
        e.start_date,
        e1.thema_132 theme_ids,
        e2.bemas_event_num_days num_days
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
}
