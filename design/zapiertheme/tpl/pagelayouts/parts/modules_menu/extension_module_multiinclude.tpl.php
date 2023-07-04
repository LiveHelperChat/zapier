<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhzapier','configure')) : ?>
    <li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('zapier/settings')?>"><i class="material-icons">integration_instructions</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Zapier');?></a></li>
<?php endif; ?>
