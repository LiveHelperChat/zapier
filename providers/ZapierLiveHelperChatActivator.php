<?php

namespace LiveHelperChatExtension\zapier\providers;

class ZapierLiveHelperChatActivator {

    // Remove SMS
    public static function remove()
    {
        $command = \erLhcoreClassModelGenericBotCommand::findOne(['filter' => ['command' => 'zapier']]);
        if (is_object($command)) {
            $command->removeThis();
        }

        if ($restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ZapierIntegration']])) {
            $restAPI->removeThis();
        }

        if ($botPrevious = \erLhcoreClassModelGenericBotBot::findOne(['filter' => ['name' => 'ZapierIntegration']])) {
            $botPrevious->removeThis();
        }
    }

    // Install SMS
    public static function installOrUpdate()
    {
        // RestAPI
        $restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ZapierIntegration']]);
        $content = json_decode(file_get_contents('extension/zapier/doc/configs/restapi-zapier.json'),true);

        $mbOptions = \erLhcoreClassModelChatConfig::fetch('zapier_options');
        $data = (array)$mbOptions->data;

        $content['configuration'] = str_replace('{zapier_host}',isset($data['endpoint']) ? $data['endpoint'] : '', $content['configuration']);

        if (!$restAPI) {
            $restAPI = new \erLhcoreClassModelGenericBotRestAPI();
        }

        $restAPI->setState($content);
        $restAPI->name = 'ZapierIntegration';
        $restAPI->saveThis();

        // Bot
        if ($botPrevious = \erLhcoreClassModelGenericBotBot::findOne(['filter' => ['name' => 'ZapierIntegration']])) {
            $botPrevious->removeThis();
        }

        $botData = \erLhcoreClassGenericBotValidator::importBot(json_decode(file_get_contents('extension/zapier/doc/configs/bot-zapier.json'),true));
        $botData['bot']->name = 'ZapierIntegration';
        $botData['bot']->updateThis(['update' => ['name']]);

        $trigger = $botData['triggers'][0];
        $actions = $trigger->actions_front;
        $actions[0]['content']['rest_api'] = $restAPI->id;
        $trigger->actions_front = $actions;
        $trigger->actions = json_encode($actions);
        $trigger->updateThis(['update' => ['actions']]);

        // Command
        $command = \erLhcoreClassModelGenericBotCommand::findOne(['filter' => ['command' => 'zapier']]);
        if (!is_object($command)) {
            $command = new \erLhcoreClassModelGenericBotCommand();
            $command->command = 'zapier';
        }
        $command->sub_command = '--silent';
        $command->bot_id = $botData['bot']->id;
        $command->trigger_id =  $botData['triggers'][0]->id;
        $command->saveThis();

    }
}

?>