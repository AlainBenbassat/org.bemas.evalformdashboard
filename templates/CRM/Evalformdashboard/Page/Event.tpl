<h3>1. Evenement details</h3>
<table id="options" class="display">
  <tr>
    <td>Titel</td>
    <td>{$eventTitle}</td>
  </tr>
  <tr>
    <td>Begindatum</td>
    <td>{$eventStartDate}</td>
  </tr>
  <tr>
    <td>Taal</td>
    <td>{$eventLanguage}</td>
  </tr>
  <tr>
    <td>Aantal dagen</td>
    <td>{$eventNumDays}</td>
  </tr>
  <tr>
    <td>Thema's</td>
    <td>{$eventThemes}</td>
  </tr>
  <tr>
    <td>Aantal deelnemers</td>
    <td>{$eventNumParticipants}</td>
  </tr>
  <tr>
    <td>Aantal evaluaties</td>
    <td>{$eventNumEvaluations}</td>
  </tr>
  <tr>
    <td>Response rate</td>
    <td>{$eventNumResponseRate}</td>
  </tr>
  <tr>
    <td>Link naar antwoorden</td>
    <td>{$eventResponseLink}</td>
  </tr>
</table>

<h3>2. Deelnemers: evaluatie evenement</h3>
<table id="options" class="display">
  <thead>
  <tr>
    <th>Algemene tevredenheid</th>
    <th>Invulling</th>
    <th>Cursusmateriaal</th>
    <th>Interactie</th>
    <th>Kwaliteit</th>
    <th>Bijgeleerd</th>
    <th>Verwachting</th>
    <th>Relevantie</th>
    <th>Administratief proces</th>
    <th>Ontvangst</th>
    <th>Catering</th>
    <th>Locatie</th>
  </tr>
  </thead>
  <tr>
    <td>{$partEventEvalAlgemeneTevredenheid}</td>
    <td>{$partEventEvalInvulling}</td>
    <td>{$partEventEvalCursusmateriaal}</td>
    <td>{$partEventEvalInteractie}</td>
    <td>{$partEventEvalKwaliteit}</td>
    <td>{$partEventEvalBijgeleerd}</td>
    <td>{$partEventEvalVerwachting}</td>
    <td>{$partEventEvalRelevantie}</td>
    <td>{$partEventEvalAdministratiefProces}</td>
    <td>{$partEventEvalOntvangst}</td>
    <td>{$partEventEvalCatering}</td>
    <td>{$partEventEvalLocatie}</td>
  </tr>
</table>

<h3>3. Deelnemers: evaluatie lesgever(s)</h3>
<table id="options" class="display">
  <thead>
    <tr>
      <th>Lesgever</th>
      <th>Expertise</th>
      <th>Didactische vaardigheden</th>
    </tr>
  </thead>
  {foreach from=$partTrainerEval item=row}
    <tr class="crm-entity">
      <td>{$row.trainer_name}</td>
      <td>{$row.expertise}</td>
      <td>{$row.didactische_vaardigheden}</td>
    </tr>
  {/foreach}
</table>

<h3>4. Lesgever(s): evaluatie evenement</h3>
<table id="options" class="display">
  <thead>
    <tr>
      <th>Algemene tevredenheid</th>
      <th>Ontvangst</th>
      <th>Catering</th>
      <th>Locatie</th>
      <th>Cursusmateriaal</th>
      <th>Interactie</th>
      <th>Verwachting</th>
    </tr>
  </thead>
  {foreach from=$trainerEventEval item=row}
    <tr class="crm-entity">
      <td>{$row.algemene_tevredenheid}</td>
      <td>{$row.ontvangst}</td>
      <td>{$row.catering}</td>
      <td>{$row.locatie}</td>
      <td>{$row.cursusmateriaal}</td>
      <td>{$row.interactie}</td>
      <td>{$row.verwachting}</td>
    </tr>
  {/foreach}
</table>
