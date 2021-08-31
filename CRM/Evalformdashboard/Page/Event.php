<?php
use CRM_Evalformdashboard_ExtensionUtil as E;

class CRM_Evalformdashboard_Page_Event extends CRM_Core_Page {

  public function run() {
    try {
      CRM_Utils_System::setTitle(E::ts('Evaluatie evenement'));

      $eventId = $this->getQueryStringParameter('event_id', 'Integer', TRUE);
      $moduleFilter = $this->getModuleFilterFromQueryString();
      $event = CRM_Evalformdashboard_Event::get($eventId, $moduleFilter);
      $participantEventEval = CRM_Evalformdashboard_Participant::getEventEval($eventId, $moduleFilter);
      $participantTrainerEval = CRM_Evalformdashboard_Participant::getTrainerEval($eventId, $moduleFilter);
      $trainerEventEval = CRM_Evalformdashboard_Trainer::getEventEval($eventId, $moduleFilter);

      $modules = $this->getModules($eventId, $moduleFilter);

      $this->assign('eventTitle', $event->title);
      $this->assign('eventStartDate', $event->start_date);
      $this->assign('eventLanguage', $event->language);
      $this->assign('eventNumHours', $event->num_hours);
      $this->assign('eventThemes', $event->themes);
      $this->assign('eventNumParticipants', $event->num_participants);
      $this->assign('eventNumEvaluations', $event->num_evaluations);
      $this->assign('eventNumResponseRate', $event->response_rate);
      $this->assign('eventParticipantResponseLink', $event->response_participant_link);
      $this->assign('eventTrainerResponseLink', $event->response_trainer_link);

      if ($modules) {
        $this->assign('modules', $modules);
      }

      if ($participantEventEval !== FALSE) {
        $this->assign('partEventEvalAlgemeneTevredenheid', $participantEventEval->algemene_tevredenheid);
        $this->assign('partEventEvalInvulling', $participantEventEval->invulling);
        $this->assign('partEventEvalCursusmateriaal', $participantEventEval->cursusmateriaal);
        $this->assign('partEventEvalInteractie', $participantEventEval->interactie);
        $this->assign('partEventEvalKwaliteit', $participantEventEval->kwaliteit);
        $this->assign('partEventEvalBijgeleerd', $participantEventEval->bijgeleerd);
        $this->assign('partEventEvalVerwachting', $participantEventEval->verwachting);
        $this->assign('partEventEvalRelevantie', $participantEventEval->relevantie);
        $this->assign('partEventEvalAdministratiefProces', $participantEventEval->administratief_proces);
        $this->assign('partEventEvalOntvangst', $participantEventEval->ontvangst);
        $this->assign('partEventEvalCatering', $participantEventEval->catering);
        $this->assign('partEventEvalLocatie', $participantEventEval->locatie);
      }

      $this->assign('partTrainerEval', $participantTrainerEval);
      $this->assign('trainerEventEval', $trainerEventEval);
    }
    catch (Exception $e) {
      CRM_Core_Session::setStatus($e->getMessage(), '', 'no-popup');
    }

    parent::run();
  }

  private function getQueryStringParameter($name, $type, $abort) {
    $v = CRM_Utils_Request::retrieve($name, $type, $this, $abort);
    return $v;
  }

  private function getModuleFilterFromQueryString() {
    $moduleFilter = $this->getQueryStringParameter('module', 'String', FALSE);
    return urldecode($moduleFilter);
  }

  private function getModules($eventId, $moduleFilter) {
    $modules = [];
    $sql = "select distinct module from civicrm_bemas_eval_participant_event where event_id = $eventId order by 1";
    $dao = CRM_Core_DAO::executeQuery($sql);
    while ($dao->fetch()) {
      $modules[] = $dao->module;
    }

    if (count($modules)) {
      return $this->convertModulesToHyperlinks($eventId, $modules, $moduleFilter);
    }
    else {
      return [];
    }
  }

  private function convertModulesToHyperlinks($eventId, $modules, $moduleFilter) {
    $links = '';
    $clearFilter = '';

    foreach ($modules as $module) {
      if ($links) {
        $links .= ' | ';
      }

      if ($moduleFilter == $module) {
        $links .= $module;

        $queryString = "reset=1&event_id=$eventId";
        $url = CRM_Utils_System::url('civicrm/evalform-dashboard-event', $queryString);
        $clearFilter = " -- <a href=\"$url\">(wis module filter)</a>";
      }
      else {
        $queryString = "reset=1&event_id=$eventId&module=" . urlencode($module);
        $url = CRM_Utils_System::url('civicrm/evalform-dashboard-event', $queryString);
        $links .= "<a href=\"$url\">$module</a>";
      }
    }

    if ($clearFilter) {
      $links .= $clearFilter;
    }

    return $links;
  }
}
