<template>
    <modal  :show = "show" @close="ChangeStateModal">
        <div v-if="!paycheck">
            <div>
                    <div class="p-4 bg-gray-600 text-white">
                        Cerra Mesa {{ $store.state.tableActivateNumber }}
                        <button @click="paidCheckFunction" class="p-1 rounded-sm bg-gray-200 hover:bg-gray-300 mr-4 p-2  border-t border-b border-l border-r border-gray-700 text-gray-800 float-right">Pagar por separado</button>
                        <div class="clear-both"></div>
                    </div>

                    <div class="p-4 bg-gray-400 text-white"><b>ADICIONES</b></div>
                    <template  v-for="(product) in $store.state.products" :key ="product.id">
                        <div v-if ="product.status>0" class="grid grid-cols-12 border-l-2 border-yellow-400" style="margin: 2px 0 0 0;" :class="(product.date_pay!=null)?'pay':''" :tip="(product.date_pay!=null)?product.tip:''">
                            <div class="col-span-2 text-left p-2">{{ product.count }} </div>
                            <div class="col-span-4 p-2"><b>{{ product.name }} </b></div>
                            <div class="col-span-2 text-center py-2 px-6"><b v-if="product.percentage>0" class="text-red-600">{{ product.percentage }}%</b> </div>
                            <div class="text-center p-2 col-span-3 text-right">
                                <span :class="(product.percentage)?'line-through text-gray-600 text-xs':'text-md'"> $ {{ product.amount }}</span>
                                <br/>
                                <span class="text-red-600 font-bold text-md" v-if="product.percentage">$ {{ product.amount - (product.amount * product.percentage / 100)}}</span>
                            </div>

                        </div>
                    </template>


                    <div class="grid grid-cols-12 border-l-2 border-gray-400" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Propina 10%</div>
                        <div class="col-span-10 text-right py-2 px-6 ">$<input v-model.number="tip" class="w-20 focus:ring-transparent" type="text"></div>
                    </div>

                   <!--<div class="grid grid-cols-12 border-l-2 border-green-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 text-left p-2"><b class="text-green-600">Subtotal </b></div>
                        <div class="col-span-10 text-right py-2 px-6"><b class="text-green-600">$ {{ $store.state.subtotal_amount_order }}</b></div>
                    </div>-->

                    <div class="grid grid-cols-12  bg-green-600 text-white" v-if="$store.state.total_amount_order_paid" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Pagado </div>
                        <div class="col-span-10 text-right py-2 px-6"><b>$ {{$store.state.total_amount_order_paid + $store.state.tip}}</b></div>
                    </div>

                    <div v-if="$store.state.discount_active>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 text-left p-2"><b class="text-red-600">Descuento</b> </div>
                        <div class="col-span-9 text-right py-2 px-6"><b class="text-red-600">$ - {{ $store.state.discount_active}}</b></div>
                    </div>

                    <div v-if="$store.state.discount_active_percentage>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 text-left p-2"><b class="text-red-600">Descuento %</b> </div>
                        <div class="col-span-9 text-right py-2 px-6"><b class="text-red-600">$ - {{ $store.state.discount_active_percentage }}</b></div>
                    </div>

                    <div class="grid grid-cols-12 bg-gray-600 text-white" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Total </div>
                        <div class="col-span-10 text-right py-2 px-6"><b>$ {{ $store.state.total_amount_order + ($store.state.total_amount_order * 0.1)}}</b></div>
                    </div>
                    <!--<div class="col-span-6"></div>-->

            </div>
            <div class="p-4 ">
                <button @click = "closeOrder" class="rounded-sm bg-green-400 hover:bg-green-500 p-2 text-white float-right ">Confirmar</button>
                    <button @click = "ChangeStateModalCloseButton" class="rounded-sm bg-gray-400 hover:bg-gray-500 mr-4 p-2  text-white float-right">Cerrar</button>

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

        }

        const setShow = ()=>{
-            emit('show');
        }
        onUpdated(() => {

            tip.value = store.state.total_amount_order * 0.1;
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
    background-color:#2ecc71;
}
</style>
