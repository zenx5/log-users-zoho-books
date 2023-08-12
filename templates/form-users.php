<?php
ob_start();
?>
<style>
    .field {
        padding:5px;
        display:flex;
        flex-direction:column;
    }
    .field .label {
        font-weight:800;
        font-size:1.1rem;
    }

    .field .double {
        display:flex;
        flex-direction:row;
        gap:1rem;
    }

    .field .double .single{
        width:100%;
    }

    input.single, textarea.single{
        border: 1px solid gray;
        background-color: #fff;
    }

    .field .helper{
        color:gray;
        font-style:italic;
    }

    .field .checks {
        display:flex;
        flex-direction:column;
        padding-left: 2rem;
    }
</style>
<form method="post">
    <input type="hidden" name="date_log" value=<?=date("d-m-Y")?>/>
    <div class="field">
        <label class="label">Nombre y Apellido</label>
        <span class="double">
            <input type="text" class="single" name="first_name"/>
            <input type="text" class="single" name="last_name"/>
        </span>
    </div>
    <div class="field">
        <label class="label">Cedula</label>
        <input type="text" class="single" name="dni" />
    </div>
    <div class="field">
        <label class="label">Teléfono</label>
        <input type="text" class="single" name="phone"/>
    </div>
    <div class="field">
        <label class="label">Servicios:</label>
        <div class="checks">
            <span>
                <input type="checkbox" id="services[0]" name="services[0]" value="Víveres"/>
                <label class="label-check" for="services[0]">Víveres</label>
            </span>
            <span>
                <input type="checkbox" id="services[1]" name="services[1]" value="Oro"/>
                <label class="label-check" for="services[1]">Oro</label>
            </span>
            <span>
                <input type="checkbox" id="services[2]" name="services[2]" value="Dolares"/>
                <label class="label-check" for="services[2]">Dolares</label>
            </span>
            <span>
                <input type="checkbox" id="services[3]" name="services[3]" value="Remesas"/>
                <label class="label-check" for="services[3]">Remesas</label>
            </span>
            <span>
                <input type="checkbox" id="services[4]" name="services[4]" value="Anway"/>
                <label class="label-check" for="services[4]">Anway</label>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Monto</label>
        <input type="number" class="single" name="mount" />
        <small class="helper">Monto en Dolares</small>
    </div>
    <div class="field">
        <label class="label">Descripción</label>
        <textarea class="single" name="description"></textarea>
    </div>
    <div class="field">
        <button type="submit">Guardar</button>
    </div>
</form>
