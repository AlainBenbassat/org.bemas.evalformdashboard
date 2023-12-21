{crmScope extensionKey='org.bemas.evalformdashboard'}
<h3>{ts}1. Evenement details{/ts}</h3>
<table id="options" class="display">
  <tr>
    <td>{ts}Titel{/ts}</td>
    <td>{$eventTitle}</td>
  </tr>
  <tr>
    <td>{ts}Begindatum{/ts}</td>
    <td>{$eventStartDate}</td>
  </tr>
  <tr>
    <td>{ts}Taal{/ts}</td>
    <td>{$eventLanguage}</td>
  </tr>
  <tr>
    <td>{ts}Aantal lesuren{/ts}</td>
    <td>{$eventNumHours}</td>
  </tr>
  {if $modules}
    <tr>
      <td>{ts}Modules{/ts}</td>
      <td>{$modules}</td>
    </tr>
  {/if}
  <tr>
    <td>{ts}Thema's{/ts}</td>
    <td>{$eventThemes}</td>
  </tr>
  <tr>
    <td>{ts}Aantal deelnemers{/ts}</td>
    <td>{$eventNumParticipants}</td>
  </tr>
  <tr>
    <td>{ts}Aantal evaluaties{/ts}</td>
    <td>{$eventNumEvaluations}</td>
  </tr>
  <tr>
    <td>{ts}Response rate{/ts}</td>
    <td>{$eventNumResponseRate}</td>
  </tr>
  <tr>
    <td>{ts}Link naar antwoorden deelnemers{/ts}</td>
    <td>{$eventParticipantResponseLink}</td>
  </tr>
  <tr>
    <td>{ts}Link naar antwoorden lesgevers{/ts}</td>
    <td>{$eventTrainerResponseLink}</td>
  </tr>
</table>

<h3>{ts}2. Deelnemers: evaluatie evenement{/ts}</h3>
<table id="options" class="display">
  <thead>
  <tr>
    <th>{ts}Algemene tevredenheid{/ts}</th>
    <th>{ts}Inhoudelijke invulling{/ts}</th>
    <th>{ts}Cursusmateriaal{/ts}</th>
    <th>{ts}Interactie{/ts}</th>
    <th>{ts}Kwaliteit{/ts}</th>
    <th>{ts}Bijgeleerd{/ts}</th>
    <th>{ts}Verwachting{/ts}</th>
    <th>{ts}Relevantie{/ts}</th>
    <th>{ts}Administratief proces{/ts}</th>
    <th>{ts}Ontvangst{/ts}</th>
    <th>{ts}Catering{/ts}</th>
    <th>{ts}Locatie{/ts}</th>
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

<h3>{ts}3. Deelnemers: feedback{/ts}</h3>

{foreach from=$partSubmissions key=question item=answerArr}
  <p><strong>{$question}:</strong></p>
    <ul>
      {foreach from=$answerArr key=submissionId item=answer}
        <li>{$answer}</li>
      {/foreach}
    </ul>
{/foreach}

<h3>{ts}4. Deelnemers: evaluatie lesgever(s){/ts}</h3>
<table id="options" class="display">
  <thead>
    <tr>
      <th>{ts}Lesgever{/ts}</th>
      <th>{ts}Expertise{/ts}</th>
      <th>{ts}Didactische vaardigheden{/ts}</th>
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

<h3>{ts}5. Lesgever(s): evaluatie evenement{/ts}</h3>
<table id="options" class="display">
  <thead>
    <tr>
      <th>{ts}Algemene tevredenheid{/ts}</th>
      <th>{ts}Ontvangst{/ts}</th>
      <th>{ts}Catering{/ts}</th>
      <th>{ts}Locatie{/ts}</th>
      <th>{ts}Cursusmateriaal{/ts}</th>
      <th>{ts}Interactie{/ts}</th>
      <th>{ts}Verwachting{/ts}</th>
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
{/crmScope}
