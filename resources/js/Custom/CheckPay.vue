<template>
        <div v-if="paycheck">
            <div>
                    <div class="p-4 text-white bg-gray-600">
                        Cerra Mesa {{ $store.state.tableActivateNumber }}
                        <button v-if="!finishCheck" @click="closemodalcloseOrder" class="float-right p-1 p-2 mr-4 text-gray-800 bg-gray-200 border-t border-b border-l border-r border-gray-700 rounded-sm hover:bg-gray-300">Pagar Todo</button>
                        <div class="clear-both"></div>
                    </div>

                    <div class="p-4 text-white bg-gray-400"><b>ADICIONES</b></div>

                    <template  v-for="(product) in $store.state.products" :key ="product.id">
                        <div v-if ="product.status>0" class="grid grid-cols-12 border-l-2 border-yellow-400" :class="(product.date_pay!=null)?'pay':''" :id="'row_check_pay_'+product.id" style="margin: 2px 0 0 0;">
                            <div class="col-span-2 p-2 text-left">{{ product.count }} </div>
                            <div class="col-span-4 p-2"><b>{{ product.name }} </b></div>
                            <div class="col-span-1 lg:px-6 py-2 text-center"><b v-if="product.percentage>0" class="text-red-600">{{ product.percentage }}%</b> </div>
                            <div class="col-span-3 lg:px-6 py-2 text-right">
                                <span :class="(product.percentage)?'line-through text-gray-600 text-xs':'text-md'"> $ {{ product.amount }}</span>
                                <br/>
                                <span class="font-bold text-red-600 text-md" v-if="product.percentage">$ {{ product.amount - (product.amount * product.percentage / 100)}}</span></div>
                            <div class="col-span-2 lg:px-6 py-2 text-right">

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
                        <div class="col-span-2 p-2 text-left">Propina 10%</div>
                        <div class="col-span-10 px-6 py-2 text-right ">$<input v-model.number="tip" class="w-20 focus:ring-transparent" type="text"></div>
                    </div>

                    <!--

                    <div class="grid grid-cols-12 border-l-2 border-green-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 p-2 text-left"><b class="text-green-600">Subtotal </b></div>
                        <div class="col-span-10 px-6 py-2 text-right"><b class="text-green-600">$ {{ $store.state.subtotal_amount_order }}</b></div>
                    </div>
                    -->

                    <div class="grid grid-cols-12 text-white bg-green-600" v-if="$store.state.total_amount_order_paid" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 p-2 text-left">Pagado </div>
                        <div class="col-span-10 px-6 py-2 text-right"><b>$ {{$store.state.total_amount_order_paid + $store.state.tip}}</b></div>
                    </div>


                    <!--
                    <div v-if="$store.state.discount_active>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 p-2 text-left"><b class="text-red-600">Descuento</b> </div>
                        <div class="col-span-9 px-6 py-2 text-right"><b class="text-red-600">$ - {{ $store.state.discount_active }}</b></div>
                    </div>
                    -->

                    <div v-if="$store.state.discount_active_percentage>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 p-2 text-left"><b class="text-red-600">Descuento %</b> </div>
                        <div class="col-span-9 px-6 py-2 text-right"><b class="text-red-600">$ - {{ $store.state.discount_active_percentage }}</b></div>
                    </div>

                    <div class="grid grid-cols-12 text-white bg-gray-600" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 p-2 text-left">Total </div>
                        <div class="col-span-10 px-6 py-2 text-right"><b>$ {{ $store.state.total_amount_order + tip }}</b></div>
                    </div>
                    <!--<div class="col-span-6"></div>-->

            </div>
            <div class="p-4 ">
                    <button @click = "closeOrderCheck" class="float-right p-2 text-white bg-green-400 rounded-sm hover:bg-green-500 ">Confirmar</button>
                    <button @click = "modalClose" class="float-right p-2 mr-4 text-white bg-gray-400 rounded-sm hover:bg-gray-500">Cerrar</button>
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
                store.state.loadData = true;
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
                    store.state.loadData = true;
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
            finishCheck.value = false;
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
