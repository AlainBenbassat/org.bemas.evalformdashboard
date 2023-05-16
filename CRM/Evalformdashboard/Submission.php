<?php

class CRM_Evalformdashboard_Submission {
  public static function getOpenAnswers($eventId, $moduleFilter) {
    $answers = [];

    $nid = self::getNid($eventId, $moduleFilter);
    if ($nid) {
      return self::getAnswersArray($nid);
    }

    return $answers;
  }

  private static function getNid($eventId, $moduleFilter) {
    $sql = "select nid from civicrm_bemas_eval_participant_event where event_id = %1";
    $sqlParams = [
      1 => [$eventId, 'Integer'],
    ];

    if ($moduleFilter) {
      $sql .= ' and module = %2';
      $sqlParams[2] = [$moduleFilter, 'String'];
    }

    return CRM_Core_DAO::singleValueQuery($sql, $sqlParams);
  }

  private static function getAnswersArray($nid) {
    $answers = [];

    $sql = "
      select
        wfc.name question,
        wfsd.data answer,
        wfs.sid submission_id
      from
        node n
      inner join
        webform wf on n.nid = wf.nid
      inner join
        webform_component wfc on wf.nid = wfc.nid
      inner JOIN
        webform_submissions wfs on wfs.nid = wfc.nid
      inner JOIN
        webform_submitted_data wfsd on wfs.sid = wfsd.sid and wfsd.cid = wfc.cid
      where
        n.type = 'webform'
      and
        wfc.form_key like 'evalform_q%'
      and
        wfc.type = 'textarea'
      and
        n.nid = $nid
      and
        ifnull(wfsd.data, '') <> ''
      order by
        wfc.weight, wfs.sid
    ";

    $result = db_query($sql);
    foreach ($result as $record) {
      $answers[$record->question][$record->submission_id] = $record->answer;
    }

    return $answers;
  }
}
