<template>
    <div v-show="$store.state.products.length" style="position: relative;">
        <template v-for="(product) in $store.state.products" :key  = "product.id" >
        <div v-if="product.date_pay==null" class="grid grid-cols-12 mt-1 bg-gray-100 border-l-4 border-gray-800 hover:bg-gray-300 product"  :id="product.id" :class ="product.status?'':'delete'" :paid = "(product.date_pay!=null)?true:false" :is_percentage = "(product.is_percentage)?true:false" :date_pay="product.date_pay">
            <div class="col-span-1 p-2 lg:col-span-2">{{ product.count }}</div>
            <div class="col-span-5 p-2"><b>{{ product.name }}</b><p><small class="text-sm">{{ product.note }}</small></p></div>

            <div class="col-span-2 p-2 text-center text-red-600 " v-if="product.percentage==0">
                <svg v-if="product.status>0 && product.date_pay==null" xmlns="http://www.w3.org/2000/svg" @click="addDiscountProduct(product)" fill="none" style="margin:0 auto; position: relative; cursor: pointer;" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" />
                </svg>
            </div>

            <div class="col-span-2 p-2 text-center text-red-600" v-if="product.percentage>0"><b>{{ product.percentage }} %</b>
                    <svg v-if="product.status>0 && product.date_pay==null" xmlns="http://www.w3.org/2000/svg" @click="delDiscountProduct(product.id)" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 cursor-pointer" style="margin: 0 auto;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            <div class="col-span-3 p-2 text-right lg:col-span-2 md:text-md lg:text-base"><span :class="(product.percentage)?'line-through text-gray-600':''"> $ {{ product.amount }}</span> <br/> <span class="font-bold text-red-600" v-if="product.percentage">$ {{ product.amount - (product.amount * product.percentage / 100)}}</span></div>
            <div class="col-span-1 pt-2 pb-2 pr-2">
                <svg v-if="product.status>0 && product.date_pay==null" :id="'trash_'+product.id"  @click="delProduct(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-center text-gray-600 hover:text-gray-900">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>

                <svg v-if="product.status>0 && product.date_pay!=null" :id="'trash_'+product.id"  @click="delProductPaid(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-center text-gray-600 hover:text-gray-900">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </div>
        </div>
        </template>

        <!--
        <div  class="grid grid-cols-12 mt-1 bg-gray-100 border-l-4 border-green-500 hover:bg-green-300">
            <div class="col-span-3 p-2 "><b class="text-green-500">Subtotal</b></div>
            <div class="col-span-5 p-2"></div>
            <div class="col-span-3 p-2 text-center"><b class="text-green-500">$ {{ $store.state.subtotal_amount_order }}</b></div>
        </div>
        -->

        <div v-if="$store.state.total_amount_order_paid>0"  class="grid grid-cols-12 mt-1 bg-gray-100 border-l-4 border-green-500">
            <div class="col-span-3 p-2 "><b class="text-green-500">Pagado</b></div>
            <div class="col-span-4 p-2"></div>
            <div class="col-span-4 p-2 text-right"><b class="text-green-500">$ {{ $store.state.total_amount_order_paid + $store.state.tip}}</b></div>
            <div class="col-span-1 pt-2 pb-2 pr-2 text-center ">
                <svg @click = "openModalPaid" class="w-6 h-6 text-green-500 hover:text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!--SE ACTiva cuando he ingresado un descuento fijo -->
        <div v-if="$store.state.discount_active>0"  class="grid grid-cols-12 mt-1 bg-gray-100 border-l-4 border-red-700">
            <div class="col-span-3 p-2 "><b class="text-red-600">Desc</b></div>
            <div class="col-span-5 p-2 lg:col-span-5"></div>
            <div class="col-span-3 p-2 text-right"><b class="text-red-600">$ -{{ $store.state.discount_active }}</b></div>
            <div class="col-span-1 pt-2 pb-2 pr-2 discount">
                <svg xmlns="http://www.w3.org/2000/svg" @click="delDiscountPermanent" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-center text-gray-600 hover:text-gray-900">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg></div>
        </div>

        <!--SE ACTIVA CUANDO HE INGRESADO UN DESCUENTO POR PORCENTAJE -->
        <div v-if="$store.state.discount_active_percentage>0"  class="grid grid-cols-12 mt-1 bg-gray-100 border-l-4 border-red-700">
            <div class="col-span-3 p-2"><b class="text-red-600">Desc %</b></div>
            <div class="col-span-5 p-2 lg:col-span-5"></div>
            <div class="col-span-3 p-2 text-right"><b class="text-red-600">$ -{{ $store.state.discount_active_percentage }}</b></div>
            <div class="col-span-1 pt-2 pb-2 pr-2 discount">
                <svg xmlns="http://www.w3.org/2000/svg" @click="delDiscountPercentage" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-center text-gray-600 hover:text-gray-900">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg></div>
        </div>



        <div class="grid grid-cols-12 mt-1 border-l-4 border-orange-700" v-if=" $store.state.showDiscount">

            <div class="col-span-12 bg-gray-300 hover:bg-gray-400 px-5 py-1">
                <div class="float-left col-span-6" > Descuento <input @keyup="applyDiscount" v-model.number = "$store.state.porcentage"  class="w-14 lg:w-28 focus:ring-transparent" type="text" name="" id=""> %</div>
                <div class="float-right col-span-6">  <input @keyup="resetApplyDiscount" v-model.number="$store.state.discount" class="float-right w-20 lg:w-28 focus:ring-transparent" type="text" name="" id=""> <span class="float-right pt-2 mr-2">$</span></div>
                <div class="clear-both"></div>
            </div>
            <div class="col-span-12 pl-2 pt-2 pb-2 pr-5">

                <button v-if="$store.state.discount>0"  @click="saveDiscount" class="float-right p-2 text-white bg-gray-800 rounded-sm hover:bg-gray-900 ">Aceptar</button>
                <button @click="cancelDiscount"  class="float-right p-2 mr-4 text-white bg-cyan-400 rounded-sm hover:bg-cyan-500">Cancelar</button>

                <div class="clear-both"></div>
            </div>
        </div>

        <div class="px-6 py-3 text-right text-white mt-1 bg-gray-800 obsolute total">
            <button @click="showformDiscount" class="float-left px-2 text-black bg-gray-300 rounded-sm">%</button>
            <b>Total: $ {{ $store.state.total_amount_order }}</b>
        </div>
        <div class="load-content" v-show = "$store.state.loadData">

            <span class="absolute h-7 w-7" style="right: 50%; margin-right: -17px; top: 50%; margin-top: -32px;">
                <span class="absolute inline-flex w-full h-full bg-red-600 rounded-full opacity-75 animate-ping"></span>
                <span class="relative inline-flex bg-red-600 rounded-full h-7 w-7"></span>
            </span>

            <div class="rounded-b-lg load-opacity"></div>
        </div>
    </div>
    <div v-show="$store.state.products.length>0" class="my-2 mr-5">
        <button @click = "openModal" class="mb-2 float-right p-2 text-white bg-gray-800 rounded-sm hover:bg-gray-900 ">Cerrar Mesa</button>
        <div class="clear-both"></div>
    </div>
    <modal-close-order  :show ="modalShowTable" @show="openModal" ></modal-close-order>
    <order-paid  :show ="modalPaid" @show="openModalPaid" @showOrderPaid ="openModalPaid"  ></order-paid >
</template>

<script>
import { useStore } from 'vuex';
import ModalCloseOrder from '@/Custom/ModalCloseOrder.vue'
import OrderPaid from '@/Custom/OrderPaid.vue'
import Swal from 'sweetalert2';
import { onMounted, onUpdated } from 'vue';
export default {

    data(){
        return{
            modalShowTable : false,
            modalPaid : false,
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
                        confirmButtonColor: '#111827',
                        cancelButtonColor: '#06B6D4',
                        confirmButtonText: 'Si, Eliminar'
                        }).then((result) => {
                        if (result.isConfirmed)
                        {
                            store.state.loadData = true;
                            store.dispatch('deleteProductOrder', product);
                        }
            })
        }

        const delProductPaid = product => {
            let title ="";
            let text ="";
            if(product.is_percentage)
            {
                title= "Seguro de revertir?";
                text= "Esta seguro que desea revetir el pago de esta compra";
            }
            else
            {
                title= "Esta compra tiene más productos relacionados";
                text= "Esta seguro que desea revetir los pagos de esta compra?";
            }
            Swal.fire({
                        title:title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#111827',
                        cancelButtonColor: '#06B6D4',
                        confirmButtonText: 'Si, Eliminar'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            store.state.loadData = true;
                            store.dispatch('deleteProductOrderPaid', product);
                        }
            })
        }

        const saveDiscount = ()=>{
            store.state.loadData = true;
            store.dispatch('applyDiscountAction');
        }

        const cancelDiscount =()=>{

            store.state.loadData = true;
            store.dispatch('cancelDiscountAction');
        }

        const delDiscountPermanent = ()=>{
            store.state.loadData = true;
            store.dispatch('deleteDiscountPermanentAction');
        }

        const delDiscountPercentage = ()=>{

            store.state.loadData = true;
            store.dispatch('deleteDiscountPercentageAction');
        }

        const delDiscountProduct = product =>{
            store.state.loadData = true;
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
                confirmButtonColor: '#111827',
                cancelButtonColor: '#06B6D4',
                showLoaderOnConfirm: true,
                preConfirm: (discount) => {
                    if(Number.isInteger(parseInt(discount)))
                    {
                        store.state.loadData = true;
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
        },
        openModalPaid(){
            this.modalPaid =!this.modalPaid;
        }
        ,
        showformDiscount()
        {
            this.$store.state.discount          =0;
            this.$store.state.porcentage        =0;
            this.$store.state.showDiscount=!this.$store.state.showDiscount;
        },

        applyDiscount(){

            /***** toma el valor total y le resta el valor del porcentaje *****/
            this.$store.state.discount=(this.$store.state.subtotal_amount_order - this.$store.state.total_amount_order_paid) * this.$store.state.porcentage/100;
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
        ModalCloseOrder,
        OrderPaid
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
    background-color:#7bed9f;
}

.paid:hover{
    background-color:#7bed9f;
}



.delete:hover{
    background-color:#bdc3c7 !important;
}
.load-content{
    position:absolute;
    width: 100%;
    height: 100%;
    top: 0;
}

.total{
    bottom: 0px;
}

.load-opacity{
    position:absolute;
    width: 100%;
    height: 100%;
    top: 0;
    background-color: black;
    opacity: 0.5;
}
</style>
