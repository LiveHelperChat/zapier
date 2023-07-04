<?php if (\erLhcoreClassModelGenericBotRestAPI::getCount(['filter' => ['name' => 'ZapierIntegration']]) == 1) : ?>
<table class="table table-sm table-borderless">
    <tr>
        <td colspan="2">
            <h6 class="fw-bold"><i class="material-icons">chat</i>Zapier</h6>
            <button onclick="lhinst.addmsgadmin(<?php echo $chat->id?>,'!zapier');" class="btn btn-xs btn-secondary">Send to Zapier</button>
            <?php if (isset($chat->chat_variables_array['zapier_informed'])) : ?>
                <span class="text-muted">Zapier was informed.</span>
            <?php else : ?>
                <span class="text-muted">Zapier was <b>NOT</b> informed yet.</span>
            <?php endif; ?>
        </td>
    </tr>
</table>
<?php endif; ?>