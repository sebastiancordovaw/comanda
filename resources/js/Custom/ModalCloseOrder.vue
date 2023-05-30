<template>
    <modal  :show = "show" @close="ChangeStateModal" >
        <div v-if="!paycheck">
            <div>
                    <div class="p-4 text-white bg-gray-800">
                        Cerra Mesa {{ $store.state.tableActivateNumber }}
                        <button @click="paidCheckFunction" class="float-right p-1 p-2 mr-4 text-gray-800 bg-gray-200 border-t border-b border-l border-r border-gray-700 rounded-sm hover:bg-gray-300">Pagar por separado</button>
                        <div class="clear-both"></div>
                    </div>

                    <div class="p-4 text-white bg-gray-400"><b>ADICIONES</b></div>
                    <template  v-for="(product) in $store.state.products" :key ="product.id">
                        <div v-if ="product.status>0" class="grid grid-cols-12 border-l-4 border-gray-800" style="margin: 2px 0 0 0;" :class="(product.date_pay!=null)?'pay':''" :tip="(product.date_pay!=null)?product.tip:''">
                            <div class="col-span-2 p-2 text-left">{{ product.count }} </div>
                            <div class="col-span-4 p-2"><b>{{ product.name }} </b></div>
                            <div class="col-span-2 px-6 py-2 text-center"><b v-if="product.percentage>0" class="text-red-600">{{ product.percentage }}%</b> </div>
                            <div class="col-span-3 p-2 text-center text-right">
                                <span :class="(product.percentage)?'line-through text-gray-600 text-xs':'text-md'"> $ {{ product.amount }}</span>
                                <br/>
                                <span class="font-bold text-red-600 text-md" v-if="product.percentage">$ {{ product.amount - (product.amount * product.percentage / 100)}}</span>
                            </div>

                        </div>
                    </template>


                    <div class="grid grid-cols-12 border-l-4 bg-gray-100 border-gray-400 mt-1">
                        <div class="col-span-2 p-1 text-left">Propina 10%</div>
                        <div class="col-span-10 px-6 py-1 text-right ">$<input v-model.number="tip" class="w-20 focus:ring-transparent" type="text"></div>
                    </div>

                   <!--<div class="grid grid-cols-12 border-l-2 border-green-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 p-2 text-left"><b class="text-green-500">Subtotal </b></div>
                        <div class="col-span-10 px-6 py-2 text-right"><b class="text-green-500">$ {{ $store.state.subtotal_amount_order }}</b></div>
                    </div>-->

                    <div class="grid grid-cols-12 bg-gray-100 border-green-500 mt-1 border-l-4" v-if="$store.state.total_amount_order_paid" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 p-2 text-left">Pagado </div>
                        <div class="col-span-10 px-6 py-2 text-right"><b>$ {{$store.state.total_amount_order_paid + $store.state.tip}}</b></div>
                    </div>

                    <div v-if="$store.state.discount_active>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 p-2 text-left"><b class="text-red-600">Descuento</b> </div>
                        <div class="col-span-9 px-6 py-2 text-right"><b class="text-red-600">$ - {{ $store.state.discount_active}}</b></div>
                    </div>

                    <div v-if="$store.state.discount_active_percentage>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 p-2 text-left"><b class="text-red-600">Descuento %</b> </div>
                        <div class="col-span-9 px-6 py-2 text-right"><b class="text-red-600">$ - {{ $store.state.discount_active_percentage }}</b></div>
                    </div>

                    <div class="grid grid-cols-12 text-white bg-gray-800" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 p-2 text-left">Total </div>
                        <div class="col-span-10 px-6 py-2 text-right"><b>$ {{ $store.state.total_amount_order + (tip)}}</b></div>
                    </div>
                    <!--<div class="col-span-6"></div>-->

            </div>
            <div class="p-4 ">
                <button @click = "closeOrder" class="float-right p-2 text-white bg-gray-800 rounded-sm hover:bg-gray-900 ">Confirmar</button>
                    <button @click = "ChangeStateModalCloseButton" class="float-right p-2 mr-4 text-white bg-cyan-400 rounded-sm hover:bg-cyan-500">Cancelar</button>

                    <div class="clear-both"></div>
                </div>
    </div>
    <check-pay v-if="paycheck" :paycheck = "paycheck" @show="setShow"  @paycheck = "payAll()"></check-pay>

    </modal>
</template>

<script>
import Modal from '@/Components/Modal.vue'
import CheckPay from '@/Custom/CheckPay.vue'
import { ref, onMounted, onUpdated, computed } from 'vue';
import { useStore } from 'vuex';

let closemodalcloseOrder = null;
const paycheck = ref(false);
let tip = ref(0);
export default {
    emits:['show'],
    setup(props,{emit})
    {
        const store = useStore();
        closemodalcloseOrder = ()=>{
            emit('show');
            paycheck.value = false;
            tip.value = 0;
            store.dispatch("getOrder");
        }

        const closeOrder = ()=>{
            emit('show');
            store.state.loadData = true;
            store.dispatch("closeOrderAction",tip.value)
        }

        const paidCheckFunction = ()=>{
            paycheck.value =!paycheck.value;
        }

        const payAll = () => {
            store.dispatch("getOrder");
            paycheck.value =!paycheck.value;
            store.dispatch('calculateAmountAction');
            tip.value = store.state.total_amount_order * 0.1;
        }

        const setShow = ()=>{
-            emit('show');
        }
        onUpdated(() =>
        {
            setTimeout(() => {
                tip.value = store.state.total_amount_order * 0.1;
            }, 1);
        })

        return {tip,closeOrder,paidCheckFunction,paycheck,setShow,payAll}

    },
    data()
    {
        return{

        }
    }
    ,components:{
        Modal ,
        CheckPay
    },
    methods:{
        ChangeStateModal()
        {
           // closemodalcloseOrder();
        }
        ,
        ChangeStateModalCloseButton()
        {
            closemodalcloseOrder();
        }
    },props:['show']
}

</script>

<style>
.pay{
    background-color:#7bed9f;
}
</style>
