<?php
    $product_id = get_option('zoho_api_item_id','');
    $product_name = get_option('zoho_api_item_name','');
    $logs = LogControl::get_all();
?>

<h1>Log Users Settings</h1>

<div>
    <h2>General Product Settings</h2>
    <table>
        <tr>
            <th style="width:200px;">Product Name</th>
            <td>
                <input type="text" value="<?=$product_name?>" name="zoho_api_item_name"/>
            </td>
        </tr>
        <tr>
            <th style="width:200px;">Product ID</th>
            <td>
                <input type="text" value="<?=$product_id?>" name="zoho_api_item_id"/>
            </td>
        </tr>
        <tr>
            <td>
                <button class="button" id="update_product">Actualizar</button>
            </td>
        </tr>
    </table>
</div>
<div style="padding:20px 0px">
    <h2>Logs</h2>
    <table style="width:100%;">
        <tr style="border:1px solid black; width:100%;">
            <th style="border:1px solid black; padding:5px 40px;">Mensaje</th>
            <th style="border:1px solid black; padding:5px 40px;">Type</th>
        </tr>
        <?php foreach($logs as $log):?>
            <?php if( str_contains($log->type, "loguser") ): ?>
                <tr style="border:1px solid black;">
                    <td style="border:1px solid black; padding:5px 40px;"><?=$log->message?></td>
                    <td style="border:1px solid black; padding:5px 40px;"><?=$log->type?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>
<script>
    jQuery('#update_product').click(async ()=>{
        const id = jQuery('input[name="zoho_api_item_id"]').val()
        const name = jQuery('input[name="zoho_api_item_name"]').val()
        const response = await fetch(ajaxurl, {
            method:'post',
            headers:{
                'Content-Type':'application/x-www-form-urlencoded'
            },
            body:[
                `action=save_product`,
                `zoho_api_item_id=${id}`,
                `zoho_api_item_name=${name}`
            ].join('&')
        })
        const result = await response.text();
        if( result ) {
            if( sessionStorage.getItem('z5_debug') ) {
                console.log( result )
            } else {
                document.location.reload();
            }
        }
    })
</script>