<template>
    <div v-show="$store.state.products.length">
        <div class="grid grid-cols-12 bg-gray-100 hover:bg-orange-300 border-orange-700 mt-1 border-l-4" v-for="(product) in $store.state.products" :key  = "product.id" :id="product.id" :class ="product.status?'':'delete'">
            <div class="col-span-2 p-2">{{ product.count }}</div>
            <div class="col-span-5 p-2"><b>{{ product.name }}</b><p><small class="text-sm">{{ product.note }}</small></p></div>
            <div class="col-span-3 text-center p-2">${{ product.amount }}</div>
            <div class="col-span-2 p-2">
                <svg v-if="product.status>0" :id="'trash_'+product.id"  @click="delProduct(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-orange-400 hover:text-orange-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg></div>
        </div>

        <div  v-if = "$store.state.discount_active" class="grid grid-cols-12 bg-gray-100 hover:bg-green-300 border-green-700 mt-1 border-l-4">
            <div class="col-span-3 p-2 "><b class="text-green-600">Subtotal</b></div>
            <div class="col-span-4 p-2"></div>
            <div class="col-span-3 text-center p-2"><b class="text-green-600">$ {{ total }}</b></div>
            <div class="col-span-2 p-2">
              
            </div>
        </div>

        <div  v-if = "$store.state.discount_active" class="grid grid-cols-12 bg-gray-100 hover:bg-red-300 border-red-700 mt-1 border-l-4 discount">
            <div class="col-span-3 p-2 "><b class="text-red-600">{{ ($store.state.percentage_active>0)?'Descuento '+$store.state.percentage_active+' %':'Descuento'}}</b></div>
            <div class="col-span-4 p-2"></div>
            <div class="col-span-3 text-center p-2"><b class="text-red-600">$ - {{ $store.state.discount_active }}</b></div>
            <div class="col-span-2 p-2">
                <svg   @click="delDiscount" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-orange-400 hover:text-orange-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg></div>
        </div>

        <div class="grid grid-cols-12  border-orange-700 mt-1 border-l-4" v-if=" $store.state.showDiscount">
            
            <div class="col-span-12 bg-yellow-200">
                <div class="float-left col-span-6 p-2" > Descuento <input @keyup="applyDiscount" v-model.number = "$store.state.porcentage"  class="w-28 focus:ring-transparent" type="text" name="" id=""> %</div>
                <div class="float-right col-span-6 p-2">  <input @keyup="applyDiscount2" v-model.number="$store.state.discount" class="w-28 float-right focus:ring-transparent" type="text" name="" id=""> <span class="float-right pt-2 mr-2">$</span></div>
                <div class="clear-both"></div>
            </div>
            <div class="col-span-12 p-4">
                
                <button v-if="$store.state.discount>0"  @click="saveDiscount" class="p-1 rounded-sm bg-orange-200 hover:bg-orange-300 p-2  border-t border-b border-l border-r float-right border-orange-700 text-orange-800 ">Aceptar</button>
                <button @click="cancelDiscount"  class="p-1 rounded-sm bg-gray-300 hover:bg-gray-200 p-2  border-t border-b border-l border-r float-right border-gray-600 text-gray-800  mr-2">Cancelar</button>
                
                <div class="clear-both"></div>
            </div>
        </div>
        
        <div class="text-right bg-gray-600 px-6 py-3 text-white">
            <button @click="sDiscount" class="float-left text-black rounded-sm px-2 bg-gray-300">%</button>
            <b>Total: $ {{ ($store.state.percentage_active>0)? (total - (total * $store.state.percentage_active/100)): (total - $store.state.discount_active)}}</b>
        </div>
    </div>
    <div v-show="$store.state.products.length>0" class="mt-2">
        <button @click = "openModal" class="p-1 rounded-sm bg-orange-200 hover:bg-orange-300 p-2  border-t border-b border-l border-r float-right border-orange-700 text-orange-800 ">Cerrar Mesa</button>
        <div class="clear-both"></div>
    </div>
    <modal-close-order :show ="modalShowTable" @show="openModal()" ></modal-close-order>
</template>

<script>
import { useStore } from 'vuex';
import ModalCloseOrder from '@/Custom/ModalCloseOrder.vue'
import Swal from 'sweetalert2';
export default {
    
    data(){
        return{
            modalShowTable : false,

        }
    },
    setup() {
        const store = useStore();

        const delProduct = product => {
            Swal.fire({
                        title: 'Seguro de eliminar?',
                        text: "¡Esta acción no se puede revertir!",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Eliminar'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            store.dispatch('deleteProductOrder', product); 
                        }
            })
        }

        const saveDiscount = ()=>{
            store.dispatch('applyDiscountAction');
        }

        const cancelDiscount =()=>{
            store.dispatch('cancelDiscountAction');
        }

        const delDiscount = ()=>{
            store.dispatch('deleteDiscountAction');
        }

        return {delProduct,saveDiscount,cancelDiscount,delDiscount}
    },
    methods:{
        openModal(){
            this.modalShowTable =!this.modalShowTable;
            if(this.modalShowTable){

                this.$store.state.tip = ((this.$store.state.total_amount_order- this.$store.state.discount_active ) * 0.1);
            }
        },
        sDiscount()
        {
            this.$store.state.discount          =0;
            this.$store.state.porcentage        =0;
            this.$store.state.showDiscount=!this.$store.state.showDiscount;
        },
        
        applyDiscount(){
            this.$store.state.discount=this.$store.state.total_amount_order * this.$store.state.porcentage/100;
            this.$store.state.discount_active=this.$store.state.discount;
        
        }
,
        applyDiscount2(){
            this.$store.state.porcentage = 0
            this.$store.state.discount_active=this.$store.state.discount;
        }
        
    },
    computed:{
        total()
        {

           
            const store = useStore();
            let total = 0;
            for(let i= 0; i< store.state.products.length; i++)
            {
                if(store.state.products[i].status>0)
                {
                    
                    total += store.state.products[i].amount;
                }
                
            }
            store.state.tip = (total * 0.1);
            return store.state.total_amount_order = total;
        }
    }
    ,
    components:{
        ModalCloseOrder
    }
}
</script>

<style>
.delete{
    color:grey;
    border-color: dimgrey;
    text-decoration:line-through;
}

.delete:hover{
    background-color:#bdc3c7 !important;
}

.discount:hover svg{
    color: white !important;
}
</style>