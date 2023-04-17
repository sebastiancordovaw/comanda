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
                            <div class="col-span-4 p-2"><b>{{ product.name }} </b></div>
                            <div class="col-span-1 text-center py-2 px-6"><b v-if="product.percentage>0" class="text-red-600">{{ product.percentage }}%</b> </div>
                            <div class="col-span-3 text-right py-2 px-6"><span :class="(product.percentage)?'line-through text-gray-600':''"> $ {{ product.amount }}</span> <span class="text-red-600 font-bold" v-if="product.percentage">$ {{ product.amount - (product.amount * product.percentage / 100)}}</span></div>
                            <div class="col-span-2 text-right py-2 px-6">

                                <div class="grid grid-cols-12">
                                    <div class="col-span-6">
                                        <input @click="totalCheck" class="checkbox" type="checkbox" :class="(product.date_pay!=null)?'paid':'nopaid'" :id="product.id" :checked="(product.date_pay!=null)?true:false" :disabled="(product.date_pay!=null)?true:false" :value="((product.amount) - (product.amount)*(product.percentage/100))" >
                                    </div>
                                    <div class="col-span-6">
                                        <span v-if="product.payment_removed!=null && product.date_pay == null">☀</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template> 
                    
                    
                    <div class="grid grid-cols-12 border-l-2 border-gray-400" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Propina 10%</div>
                        <div class="col-span-10 text-right py-2 px-6 ">$<input v-model.number="tip" class="w-20 focus:ring-transparent" type="text"></div>
                    </div>

                    <!--

                    <div class="grid grid-cols-12 border-l-2 border-green-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 text-left p-2"><b class="text-green-600">Subtotal </b></div>
                        <div class="col-span-10 text-right py-2 px-6"><b class="text-green-600">$ {{ $store.state.subtotal_amount_order }}</b></div>
                    </div>
                    -->
                    
                    <div class="grid grid-cols-12  bg-green-600 text-white" v-if="$store.state.total_amount_order_paid" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Pagado </div>
                        <div class="col-span-10 text-right py-2 px-6"><b>$ {{$store.state.total_amount_order_paid + $store.state.tip}}</b></div>
                    </div>


                    <div v-if="$store.state.discount_active>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 text-left p-2"><b class="text-red-600">Descuento</b> </div>
                        <div class="col-span-9 text-right py-2 px-6"><b class="text-red-600">$ - {{ $store.state.discount_active }}</b></div>
                    </div>

                    <div v-if="$store.state.discount_active_percentage>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 text-left p-2"><b class="text-red-600">Descuento %</b> </div>
                        <div class="col-span-9 text-right py-2 px-6"><b class="text-red-600">$ - {{ $store.state.discount_active_percentage }}</b></div>
                    </div>
                 
                    <div class="grid grid-cols-12 bg-gray-600 text-white" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Total </div>
                        <div class="col-span-10 text-right py-2 px-6"><b>$ {{ $store.state.total_amount_order + tip }}</b></div>
                    </div>
                    <!--<div class="col-span-6"></div>-->
                    
            </div>
            <div class="p-4 ">
                    <button v-if="!finishCheck" @click = "closeOrderCheck" class="p-1 rounded-sm bg-orange-200 hover:bg-orange-300 mr-4 p-2  border-t border-b border-l border-r border-orange-700 text-orange-800 float-right ">Confirmar</button>
                    <button @click = "modalClose" class="p-1 rounded-sm bg-gray-200 hover:bg-gray-300 mr-4 p-2  border-t border-b border-l border-r border-gray-700 text-gray-800 float-right">Cerrar</button>
                   
                    <div class="clear-both"></div>
                </div>
    </div>
</template>

<script>
import { ref, onMounted, onUpdated, computed } from 'vue';
import { useStore } from 'vuex';

let closemodalcloseOrder = null;
let tip = ref(0);
let idSelect = ref([]);
let finishCheck = ref(false);
export default {
    emits:['show','paycheck'],
    setup(props,{emit}){


        const store = useStore();
        closemodalcloseOrder = ()=>
        { 
            emit('paycheck');
            tip.value = 0;
        }
        
        const closeOrderCheck = ()=>{
            if(idSelect.value.length)
            {
                store.dispatch("closeOrderCheckAction",{'ids':idSelect.value,'tip':tip.value})
                tip.value = 0;
                
                let checkbox = document.getElementsByClassName("checkbox");
                let count = 0;
                for(let i = 0; i< checkbox.length ; i++)
                {
                    if(checkbox[i].checked == true)
                    {  
                        count++;
                    }
                }

                if(checkbox.length == count){
                    finishCheck.value = true;
                    store.dispatch("closeOrderCheckFinallyAction",{'table': store.state.tableActivate,'order':store.state.orderActivate})
                    modalClose();
                }


            }
        }

        const modalCloseFinish = () =>{
          
        }

        const modalClose= () =>{
            emit("show");
            emit('paycheck');
        }

        const totalCheck = (event) =>
        {
            tip.value =0;
            let nopaid = document.getElementsByClassName("nopaid");
            let value = 0;
            idSelect.value = [];
            for(let i = 0; i< nopaid.length ; i++)
            {
                if(nopaid[i].checked == true)
                {  
                    idSelect.value.push(nopaid[i].id); 
                    value+=parseInt(nopaid[i].value);
                }
            }

            store.state.total_amount_order = value;

            if(value == 0)
            {
                tip.value = 0;
            }
            else{
                tip.value = Math.ceil((value)* 0.1);
            }
        }

        return {finishCheck,idSelect,closeOrderCheck,closemodalcloseOrder,modalClose,modalCloseFinish,totalCheck,tip}
    },
    data()
    {
        return{

        }
    },
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
        const store = useStore();
        store.state.total_amount_order = 0;
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