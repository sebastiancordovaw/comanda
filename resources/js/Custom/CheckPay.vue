<template>
        <div v-if="paycheck">
            <div>
                    <div class="p-4 bg-gray-600 text-white">
                        Cerra Mesa {{ $store.state.tableActivateNumber }}
                        <button v-if="!finishCheck" @click="closemodalcloseOrder" class="p-1 rounded-sm bg-gray-200 hover:bg-gray-300 mr-4 p-2  border-t border-b border-l border-r border-gray-700 text-gray-800 float-right">Pagar Todo</button>
                        <div class="clear-both"></div>
                    </div>
                    
                    <div class="p-4 bg-gray-400 text-white"><b>ADICIONES</b></div>
                    <template  v-for="(product) in $store.state.products" :key ="product.id">
                        <div v-if ="product.status>0" class="grid grid-cols-12 border-l-2 border-orange-600" :class="(product.date_pay!=null)?'pay':''" :id="'row_check_pay_'+product.id" style="margin: 2px 0 0 0;">
                            <div class="col-span-2 text-left p-2">{{ product.count }} </div>
                            <div class="col-span-6 p-2"><b>{{ product.name }} </b></div>
                            <div class="col-span-2 text-right py-2 px-6">$ {{ product.amount }}</div>
                            <div class="col-span-2 text-right py-2 px-6"><input @click="totalCheck" type="checkbox" :class="(product.date_pay!=null)?'paid':''" :id="'check_pay_'+product.id" :tip="(product.date_pay!=null)?product.tip:(tipcheck/isCheck)" :checked="(product.date_pay!=null)?true:false" :disabled="(product.date_pay!=null)?true:false" :value="product.amount" :ref="(product.date_pay!=null)?'':'newCheckPay'" :name="product.id" ></div>
                        </div>
                    </template> 
                    
                    <div class="grid grid-cols-12 border-l-2 border-green-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 text-left p-2"><b class="text-green-600">Subtotal </b></div>
                        <div class="col-span-10 text-right py-2 px-6"><b class="text-green-600">$ {{  subtotal }}</b></div>
                    </div>

                    <div v-if="$store.state.percentage_active>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 text-left p-2"><b class="text-red-600">Descuento <span v-if="$store.state.percentage_active>0">{{ $store.state.percentage_active+' %' }}</span></b> </div>
                        <div class="col-span-9 text-right py-2 px-6"><b class="text-red-600">$ - {{ checkDiscount }}</b></div>
                    </div>

                    <div  class="grid grid-cols-12 border-l-2 border-gray-400" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Propina</div>
                        <div class="col-span-10 text-right py-2 px-6 ">$<input v-model.number="tipcheck" @keyup="tipUpdate" class="w-20 focus:ring-transparent" type="text"></div>
                    </div>
                    

                    <div class="grid grid-cols-12 bg-gray-600 text-white" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Total Parcial</div>
                        <div class="col-span-10 text-right py-2 px-6"><b>$ {{ amountCheck + tipcheck }}</b></div>
                    </div>

                    <div v-if="paid>0" class="grid grid-cols-12 bg-green-600 text-white" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Pagado </div>
                        <div class="col-span-10 text-right py-2 px-6"><b>$ {{ (paid - (paid*$store.state.percentage_active/100)) + tipParcial}}</b></div>
                    </div>

                    <div class="grid grid-cols-12 bg-gray-600 text-white" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Total Global </div>
                        <div class="col-span-10 text-right py-2 px-6"><b>$ {{ ($store.state.total_amount_order - ($store.state.total_amount_order*$store.state.percentage_active/100))  + ($store.state.total_amount_order - ($store.state.total_amount_order*$store.state.percentage_active/100)) *0.1 }} - Propina : $ {{ ($store.state.total_amount_order - ($store.state.total_amount_order*$store.state.percentage_active/100)) *0.1 }} </b></div>
                    </div>
                    <!--<div class="col-span-6"></div>-->
                    
            </div>
            <div class="p-4 ">
                    <button v-if="!finishCheck" @click = "closeOrderF" class="p-1 rounded-sm bg-orange-200 hover:bg-orange-300 mr-4 p-2  border-t border-b border-l border-r border-orange-700 text-orange-800 float-right ">Confirmar</button>
                    <button v-if="!finishCheck" @click = "modalClose" class="p-1 rounded-sm bg-gray-200 hover:bg-gray-300 mr-4 p-2  border-t border-b border-l border-r border-gray-700 text-gray-800 float-right">Cancelar</button>
                    <button v-if="finishCheck"  @click = "modalCloseFinish" class="p-1 rounded-sm bg-gray-200 hover:bg-gray-300 mr-4 p-2  border-t border-b border-l border-r border-gray-700 text-gray-800 float-right">Cerrar</button>
                    <div class="clear-both"></div>
                </div>
    </div>
</template>

<script>
import { ref, onMounted, onUpdated, computed } from 'vue';
import { useStore } from 'vuex';

let closemodalcloseOrder = null;
let idsCheck = [];
let paid = ref(0);
let tipParcial = ref(0);
let finishCheck = ref(false);
export default {
    emits:['show','paycheck'],
    setup(props,{emit}){

        
        const store = useStore();
        closemodalcloseOrder = ()=>
        { 
            emit('paycheck'); 
        }
        
        const closeOrderF = ()=>{

        }

        const modalCloseFinish = () =>{
            emit("show");
            emit('paycheck');
            store.state.productsSearch  = [],
            store.state.tableActivateNumber = 0,
            store.state.showDiscount        = false;
            delete store.state.cart1[store.state.tableActivate];
            store.state.products        = []; 
            store.state.total_amount_order = 0,
            store.state.tip               =0;
            store.state.discount          =0;
            store.state.porcentage        =0;
            store.state.discount_active   =0;
            store.state.percentage_active =0;
            store.state.tableActivate   = 0;

            localStorage.setItem("table",null);
            localStorage.setItem("cart1",{});
            localStorage.getItem("tableNumber",0);
        }

        const modalClose= () =>{
            emit("show");
            emit('paycheck');
        }

        

        return {closeOrderF,closemodalcloseOrder,paid,tipParcial,idsCheck,modalClose,finishCheck,modalCloseFinish}
    },
    data()
    {
        return{
          amountCheck :0,
          tipcheck : 0,
          checkDiscount:0,
          subtotal : 0,
          isCheck :0,
          
        }
    },
    onUpdated()
    {
   
    }
    ,
    components:{
    },
    methods:{
        ChangeStateModal()
        {
            closemodalcloseOrder();
        }
        
    },
    beforeMount()
    {

    },
    mounted()
    {
    
    }
    ,updated(){
        
    },
    computed:{
       
    },
    props:["paycheck"]
}
</script>

<style>
.pay{
    background-color:#2ecc71;
}
</style>