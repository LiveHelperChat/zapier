<?php

$tpl = erLhcoreClassTemplate::getInstance('lhzapier/settings.tpl.php');

$mbOptions = erLhcoreClassModelChatConfig::fetch('zapier_options');
$data = (array)$mbOptions->data;

if (isset($_POST['StoreOptions'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('zapier/settings');
        exit;
    }

    $definition = array(
        'endpoint' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'endpoint' )) {
        $data['endpoint'] = $form->endpoint;
    } else {
        $data['endpoint'] = '';
    }

    $mbOptions->explain = '';
    $mbOptions->type = 0;
    $mbOptions->hidden = 1;
    $mbOptions->identifier = 'zapier_options';
    $mbOptions->value = serialize($data);
    $mbOptions->saveThis();

    // Update Rest API endpoint
    $restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ZapierIntegration']]);

    // Update Rest API if exists
    if (is_object($restAPI)) {
        $configurationArray = $restAPI->configuration_array;
        $configurationArray['parameters'][0]['suburl'] = $data['endpoint'];
        $restAPI->configuration_array = $configurationArray;
        $restAPI->configuration = json_encode($configurationArray);
        $restAPI->updateThis();
    }

    $tpl->set('updated','done');
}

if (isset($_POST['CreateUpdateRestAPI'])) {
    \LiveHelperChatExtension\zapier\providers\ZapierLiveHelperChatActivator::installOrUpdate();
    $tpl->set('updated','done');
}

if (isset($_POST['RemoveRestAPI'])) {
    \LiveHelperChatExtension\zapier\providers\ZapierLiveHelperChatActivator::remove();
    $tpl->set('updated','done');
}

$tpl->set('mb_options',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('zapier/settings'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Zapier settings')
    )
);

?>