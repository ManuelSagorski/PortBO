<div id="dataProtectionLayer">
	<div id="dataProtectionContend" class="ui raised segment">
		<h3>
            <?php
            /** @var $t */
            $t->_('privacy-consent-title');
            ?>
        </h3>
        <p>
            <?php
            $t->_('dear-user');
            /** @var \bo\components\classes\User $user */
            echo ' ' . $user->getFirstName() . ',';
            ?>
        </p>
		<p><?php $t->_('privacy-consent-intro'); ?></p>
		<ul>
			<li><?php $t->_('privacy-consent-data-collection'); ?></li>
			<li><?php $t->_('privacy-consent-data-sharing'); ?></li>
			<li><?php $t->_('privacy-consent-no-personal-info'); ?></li>
			<li>
                <?php $t->_('privacy-consent-accept'); ?>
				<a class="markedLink" href="/bo/datenschutz.php" target="_blank"><?php $t->_('privacy-consent-policy-title'); ?></a>
			</li>
		</ul>
		
		<div class="ui two column grid">
			<div class="column">
                <div class="ui vertical animated negative button" tabindex="2" onClick='window.location.href = "../index.php?logout"'>
                    <div class="hidden content"><i class="thumbs down outline icon"></i></div>
                    <div class="visible content"><?php $t->_('decline'); ?></div>
                </div>
			</div>
			<div class="column right aligned">
                <div class="ui vertical animated positive button" tabindex="1" onClick='acceptDataprotection();'>
                    <div class="hidden content"><i class="thumbs up outline icon"></i></div>
                    <div class="visible content"><?php $t->_('accept'); ?></div>
                </div>
			</div>
		</div>				
	</div>
</div>