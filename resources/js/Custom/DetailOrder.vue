<template>
    <div v-show="$store.state.products.length">
        <div class="grid grid-cols-12 bg-gray-100 hover:bg-orange-300 border-orange-700 mt-1 border-l-4 product" v-for="(product) in $store.state.products" :key  = "product.id" :id="product.id" :class ="product.status?'':'delete'" :paid = "(product.date_pay!=null)?true:false" :date_pay="product.date_pay">
            <div class="col-span-2 p-2">{{ product.count }}</div>
            <div class="p-2 col-span-4"><b>{{ product.name }}</b><p><small class="text-sm">{{ product.note }}</small></p></div>
            <div class="col-span-2 text-center text-red-600 p-2 " v-if="product.percentage==0"> 
                <svg v-if="product.status>0 && product.date_pay==null" xmlns="http://www.w3.org/2000/svg" @click="addDiscountProduct(product)" fill="none" style="margin:0 auto; position: relative; cursor: pointer;" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181" />
                </svg>
            </div>
    
            <div class="col-span-2  text-red-600 p-2 " v-if="product.percentage>0"><b>{{ product.percentage }} %</b> 
                    <svg v-if="product.status>0 && product.date_pay==null" xmlns="http://www.w3.org/2000/svg" @click="delDiscountProduct(product.id)" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 float-right">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            <div class="text-center p-2 col-span-3">${{ product.amount }}</div>
            <div class="col-span-1 p-2">
                <svg v-if="product.status>0 && product.date_pay==null" :id="'trash_'+product.id"  @click="delProduct(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-orange-400 hover:text-orange-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>

                <svg v-if="product.status>0 && product.date_pay!=null" :id="'trash_'+product.id"  @click="delProductPaid(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-orange-400 hover:text-orange-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>
        </div>

        <div  class="grid grid-cols-12 bg-gray-100 hover:bg-green-300 border-green-700 mt-1 border-l-4">
            <div class="col-span-3 p-2 "><b class="text-green-600">Subtotal</b></div>
            <div class="col-span-5 p-2"></div>
            <div class="col-span-3 text-center p-2"><b class="text-green-600">$ {{ $store.state.subtotal_amount_order }}</b></div>
        </div>

        <div v-if="$store.state.total_amount_order_paid>0"  class="grid grid-cols-12 bg-gray-100 hover:bg-green-300 border-green-700 mt-1 border-l-4">
            <div class="col-span-3 p-2 "><b class="text-green-600">Pagado</b></div>
            <div class="col-span-5 p-2"></div>
            <div class="col-span-3 text-center p-2"><b class="text-green-600">$ {{ $store.state.total_amount_order_paid }}</b></div>
        </div>

        <!--SE ACTiva cuando he ingresado un descuento fijo -->
        <div v-if="$store.state.discount_active>0"  class="grid grid-cols-12 bg-gray-100 hover:bg-red-300 border-red-700 mt-1 border-l-4">
            <div class="col-span-3 p-2 "><b class="text-red-600">Descuento</b></div>
            <div class="col-span-5 p-2"></div>
            <div class="col-span-3 text-center p-2"><b class="text-red-600">$ {{ $store.state.discount_active }}</b></div>
            <div class="col-span-1 p-2 discount">
                <svg xmlns="http://www.w3.org/2000/svg" @click="delDiscountPermanent" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-orange-400 hover:text-orange-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg></div>
        </div>

        <!--SE ACTIVA CUANDO HE INGRESADO UN DESCUENTO POR PORCENTAJE -->
        <div v-if="$store.state.discount_active_percentage>0"  class="grid grid-cols-12 bg-gray-100 hover:bg-red-300 border-red-700 mt-1 border-l-4">
            <div class="col-span-3 p-2 "><b class="text-red-600">Descuento %</b></div>
            <div class="col-span-5 p-2"></div>
            <div class="col-span-3 text-center p-2"><b class="text-red-600">$ {{ $store.state.discount_active_percentage }}</b></div>
            <div class="col-span-1 p-2 discount">
                <svg xmlns="http://www.w3.org/2000/svg" @click="delDiscountPercentage" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-orange-400 hover:text-orange-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg></div>
        </div>

        

        <div class="grid grid-cols-12  border-orange-700 mt-1 border-l-4" v-if=" $store.state.showDiscount">
            
            <div class="col-span-12 bg-yellow-200">
                <div class="float-left col-span-6 p-2" > Descuento <input @keyup="applyDiscount" v-model.number = "$store.state.porcentage"  class="w-28 focus:ring-transparent" type="text" name="" id=""> %</div>
                <div class="float-right col-span-6 p-2">  <input @keyup="resetApplyDiscount" v-model.number="$store.state.discount" class="w-28 float-right focus:ring-transparent" type="text" name="" id=""> <span class="float-right pt-2 mr-2">$</span></div>
                <div class="clear-both"></div>
            </div>
            <div class="col-span-12 p-4">
                
                <button v-if="$store.state.discount>0"  @click="saveDiscount" class="p-1 rounded-sm bg-orange-200 hover:bg-orange-300 p-2  border-t border-b border-l border-r float-right border-orange-700 text-orange-800 ">Aceptar</button>
                <button @click="cancelDiscount"  class="p-1 rounded-sm bg-gray-300 hover:bg-gray-200 p-2  border-t border-b border-l border-r float-right border-gray-600 text-gray-800  mr-2">Cancelar</button>
                
                <div class="clear-both"></div>
            </div>
        </div>
        
        <div class="text-right bg-gray-600 px-6 py-3 text-white">
            <button @click="showformDiscount" class="float-left text-black rounded-sm px-2 bg-gray-300">%</button>
            <b>Total: $ {{ $store.state.total_amount_order }}</b>
        </div>
    </div>
    <div v-show="$store.state.products.length>0" class="mt-2">
        <button @click = "openModal" class="p-1 rounded-sm bg-orange-200 hover:bg-orange-300 p-2  border-t border-b border-l border-r float-right border-orange-700 text-orange-800 ">Cerrar Mesa</button>
        <div class="clear-both"></div>
    </div>
    <modal-close-order  :show ="modalShowTable" @show="openModal()" ></modal-close-order>
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

        const delProductPaid = product => {
            Swal.fire({
                        title: 'Seguro de eliminar?',
                        text: "¡Esta acción no se puede revertir!, se eliminara el pago de todos los productos relacionados a esta compra",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Eliminar'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            store.dispatch('deleteProductOrderPaid', product); 
                            let products =  document.getElementsByClassName("product");
                            let date_pay =  product.date_pay;
                            console.log(date_pay);
                            for(let i = 0; i < products.length;  i++)
                            { 
                                console.log(products[i].getAttribute('date_pay'));
                                    if(products[i].getAttribute('date_pay')==date_pay)
                                    {
                                        products[i].classList.remove("paid");
                                    } 
                            }

                        }
            })
        }

        const saveDiscount = ()=>{
            store.dispatch('applyDiscountAction');
        }

        const cancelDiscount =()=>{
            store.dispatch('cancelDiscountAction');
        }

        const delDiscountPermanent = ()=>{
            store.dispatch('deleteDiscountPermanentAction');
        }

        const delDiscountPercentage = ()=>{
            store.dispatch('deleteDiscountPercentageAction');
        }

        const delDiscountProduct = product =>{
            store.dispatch('delDiscountProductAction',product);
        }

        const addDiscountProduct = product =>{
            
                Swal.fire({
                title: 'Agregar un Descuento',
                input: 'text',
                inputValue: '',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                preConfirm: (discount) => {
                    if(Number.isInteger(parseInt(discount)))
                    {
                        store.dispatch('addDiscountProductAction',{'product':product,'discount': discount});
                    }
                }
            })
        }

        return {delProductPaid,delProduct,saveDiscount,cancelDiscount,delDiscountPermanent,delDiscountPercentage,delDiscountProduct,addDiscountProduct}
    },
    methods:{
        openModal(){
          
            this.modalShowTable =!this.modalShowTable;
            if(this.modalShowTable)
            {

            }
        },
        showformDiscount()
        {
            this.$store.state.discount          =0;
            this.$store.state.porcentage        =0;
            this.$store.state.showDiscount=!this.$store.state.showDiscount;
        },
        
        applyDiscount(){

            /***** toma el valor total y le resta el valor del porcentaje *****/
            this.$store.state.discount=this.$store.state.subtotal_amount_order * this.$store.state.porcentage/100;
        },
        resetApplyDiscount(){
            this.$store.state.porcentage = 0
        },
        total()
        {
            this.$store.dispatch('calculateAmountAction');
        }        
    },
    updated()
    {
       let products =  document.getElementsByClassName("product");
       for(let i = 0; i < products.length;  i++)
       { 
            if(products[i].getAttribute('paid')!=='false' && !products[i].className.match(/\bdelete\b/))
            {
                products[i].classList.add("paid");
            } 
       }
    },
    computed:{


        
    },
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

.paid{
    background-color:#2ecc71;
}

.paid:hover{
    background-color:#2ecc71;
}



.delete:hover{
    background-color:#bdc3c7 !important;
}

.discount:hover svg{
    color: white !important;
}
</style>