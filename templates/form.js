console.log( 'users', wp_customers )

const { createApp } = Vue

createApp({
    data() {
        return {
            search: "",
            customers: wp_customers,
            customerId: -1,
            client: -1,
            firstName: "",
            lastName: "",
            dni: "",
            phone: "",
            services: [],
            mount:0,
            description:""
        }
    },
    computed:{
        customersFiltered: function() {
            return this.customers.filter( customer => {
                if( this.search==="" ) return true;
                return (
                    String(customer.data.display_name).toLowerCase().includes( this.search.toLowerCase() ) ||
                    String(customer.data.dni).toLowerCase().includes( this.search.toLowerCase() )
                )
            })
        }
    },
    methods: {
        selectUser(event) {
            console.log( this.services )
            console.log( event.target?.value )
            if( event.target?.value != -1 ) {
                const customer = this.customers.find( customer => customer.ID === parseInt(event.target.value) )
                const [ firstName, ...lastName] = customer.data.display_name.split(" ")
                console.log( 'customer', customer )
                this. customerId = customer.data.contact_id
                this.firstName = firstName
                this.lastName = lastName.join(" ")
                this.dni = customer.data.dni
                this.phone = customer.billing.billing_phone
            } else {
                this.firstName = ""
                this.lastName = ""
                this.dni = ""
                this.phone = ""
                this.services = []
                this.mount =0
                this.description =""
            }
        }
    }
}).mount("#form-app")