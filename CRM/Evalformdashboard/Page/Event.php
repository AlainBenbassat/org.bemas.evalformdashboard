<?php
use CRM_Evalformdashboard_ExtensionUtil as E;

class CRM_Evalformdashboard_Page_Event extends CRM_Core_Page {

  public function run() {
    try {
      CRM_Utils_System::setTitle(E::ts('Evaluatie evenement'));

      $eventId = $this->getQueryStringParameter('event_id', 'Integer');
      $event = CRM_Evalformdashboard_Event::get($eventId);
      $participantEventEval = CRM_Evalformdashboard_Participant::getEventEval($eventId);
      $participantTrainerEval = CRM_Evalformdashboard_Participant::getTrainerEval($eventId);
      $trainerEventEval = CRM_Evalformdashboard_Trainer::getEventEval($eventId);

      $this->assign('eventTitle', $event->title);
      $this->assign('eventStartDate', $event->start_date);
      $this->assign('eventLanguage', $event->language);
      $this->assign('eventNumDays', $event->num_days);
      $this->assign('eventThemes', $event->themes);
      $this->assign('eventNumParticipants', $event->num_participants);
      $this->assign('eventNumEvaluations', $event->num_evaluations);
      $this->assign('eventNumResponseRate', $event->response_rate);
      $this->assign('eventParticipantResponseLink', $event->response_participant_link);
      $this->assign('eventTrainerResponseLink', $event->response_trainer_link);

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

  private function getQueryStringParameter($name, $type) {
    $v = CRM_Utils_Request::retrieve($name, $type, $this, TRUE);
    return $v;
  }
}
