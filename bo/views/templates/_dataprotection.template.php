<div id="dataProtectionLayer">
	<div id="dataProtectionContend" class="ui raised segment">
		<h3>Zustimmung zum Datenschutz</h3>
		<p>Lieber <?php echo $user->getFirstName(); ?>,
		<p>aus Datenschutzgründen ist es erforderlich, dass du vor der Nutzung von 'Backoffice' folgenden Bedingungen zustimmst:</p>
		<ul>
			<li>
				Ich bin einverstanden damit, dass meine persönlichen Daten (Name, Email Adresse, Handynummer) auf der Seite Backoffice gespeichert werden.
				Die Daten werden ausschließlich für die Organisation des Hafendienstes verwendet und an niemanden weitergeleitet.
			</li>
			<li>
				Ich bin einverstanden damit, dass andere Mitarbeiter aus meiner Hafengruppe und auch aus anderen Hafengruppen meine im ersten Punkt genannten Kontaktdaten einsehen und mich kontaktieren können.
			</li>
			<li>
				Ich habe verstanden, dass ich im Backoffice keine Informationen zu Privatpersonen hinterlegen darf.
				Dazu gehören z.B. Kontaktdaten von Seeleuten sowie Mitarbeiter von Agenturen, Hafenbehörden oder anderer Firmen.
			</li>
			<li>
				Ich bin mit den allgemeinen Datenschutzbedingungen (besonders im Sinne der EU-DSGVO) von https://port-mission.de einverstanden. Diese können hier eingesehen werden: 
				<a class="markedLink" href="https://port-mission.de/bo/datenschutz.php" target="_blank">Datenschutzbeingungen</a>
			</li>
		</ul>
		
		<div class="ui two column grid">
			<div class="column">
                <div class="ui vertical animated negative button" tabindex="2" onClick='window.location.href = "../index.php?logout"'>
                    <div class="hidden content"><i class="thumbs down outline icon"></i></div>
                    <div class="visible content">Ablehnen</div>
                </div>
			</div>
			<div class="column right aligned">
                <div class="ui vertical animated positive button" tabindex="1" onClick='acceptDataprotection();'>
                    <div class="hidden content"><i class="thumbs up outline icon"></i></div>
                    <div class="visible content">Zustimmen</div>
                </div>
			</div>
		</div>				
	</div>
</div>