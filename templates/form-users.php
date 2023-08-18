
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

    .field .checks span {
        display: flex;
        flex-direction: row;
        gap: 1rem;
    }

    .field .checks * {
        cursor: pointer;
    }

    .field .checks label.checked {
        font-weight: bold;
    }

    .label-checked {
        font-weight: bold;
    }
</style>
<form method="post" id="form-app">
    <input type="hidden" name="date_log" value="<?=date("Y-m-d")?>"/>
    <div style="display:flex; flex-direction:row; gap:5px;">
        <div class="field" style="width:100%">
            <label class="label" >Cliente</label>
            <select class="single" style="width:100%" v-model="client" name="id" v-on:change="selectUser">
                <option value="-1">Nuevo Usuario</option>
                <option v-for="customer in customersFiltered " :value="customer.data.ID">{{customer.data.display_name}}</option>
            </select>
            <input type="hidden" name="contact_id" v-model="customerId"/>
        </div>
        <div class="field" style="width:100%">
            <label class="label">
                <b>Buscar</b>
                <input type="text" class="single" placeholder="Buscar..." style="width:100%" v-model="search" v-on:keyup="searchUser" />
            </label>
        </div>
	</div>
    <div class="field" v-if="client==-1">
        <label class="label">Nombre y Apellido</label>
        <span class="double">
            <input type="text" class="single" name="first_name" v-model="firstName"/>
            <input type="text" class="single" name="last_name" v-model="lastName"/>
        </span>
    </div>
    <div v-else>
        <input type="hidden" name="first_name" v-model="firstName"/>
        <input type="hidden" name="last_name" v-model="lastName"/>
    </div>
    <div class="field">
        <label class="label">Cedula</label>
        <input type="text" class="single" name="dni" v-model="dni"/>
    </div>
    <div class="field">
        <label class="label">Teléfono</label>
        <input type="text" class="single" name="phone" v-model="phone"/>
    </div>
    <div class="field">
        <label class="label">Servicios:</label>
        <div class="checks">
            <span>
                <input type="checkbox" id="services[0]" name="services[0]" value="Víveres" v-model="services"/>
                <label v-bind:class="services.includes('Víveres') ? 'label-checked' : 'label-check'" for="services[0]">Víveres</label>
            </span>
            <span>
                <input type="checkbox" id="services[1]" name="services[1]" value="Oro" v-model="services"/>
                <label v-bind:class="services.includes('Oro') ? 'label-checked' : 'label-check'" for="services[1]">Oro</label>
            </span>
            <span>
                <input type="checkbox" id="services[2]" name="services[2]" value="Dolares" v-model="services"/>
                <label v-bind:class="services.includes('Dolares') ? 'label-checked' : 'label-check'" for="services[2]">Dolares</label>
            </span>
            <span>
                <input type="checkbox" id="services[3]" name="services[3]" value="Remesas" v-model="services"/>
                <label v-bind:class="services.includes('Remesas') ? 'label-checked' : 'label-check'" for="services[3]">Remesas</label>
            </span>
            <span>
                <input type="checkbox" id="services[4]" name="services[4]" value="Anway" v-model="services"/>
                <label v-bind:class="services.includes('Anway') ? 'label-checked' : 'label-check'" for="services[4]">Anway</label>
            </span>
        </div>
    </div>
    <div class="field">
        <label class="label">Monto</label>
        <input type="number" class="single" name="mount" v-model="mount"/>
        <small class="helper">Monto en Dolares</small>
    </div>
    <div class="field">
        <label class="label">Descripción</label>
        <textarea class="single" name="description" v-model="description"></textarea>
    </div>
    <div class="field">
        <button type="submit">Guardar</button>
    </div>
</form>
