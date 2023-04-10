<template>
    <modal  :show = "show" @close="ChangeStateModal">

        <div v-if="!paycheck">
            <div>
                    <div class="p-4 bg-gray-600 text-white">
                        Cerra Mesa {{ $store.state.tableActivateNumber }}
                        <button @click="patCheckFunction" class="p-1 rounded-sm bg-gray-200 hover:bg-gray-300 mr-4 p-2  border-t border-b border-l border-r border-gray-700 text-gray-800 float-right">Pagar por separado</button>
                        <div class="clear-both"></div>
                    </div>
                    
                    <div class="p-4 bg-gray-400 text-white"><b>ADICIONES</b></div>
                    <template  v-for="(product) in $store.state.products" :key ="product.id">
                        <div v-if ="product.status>0" class="grid grid-cols-12 border-l-2 border-orange-600" style="margin: 2px 0 0 0;">
                            <div class="col-span-2 text-left p-2">{{ product.count }} </div>
                            <div class="col-span-8 p-2"><b>{{ product.name }} </b></div>
                            <div class="col-span-2 text-right py-2 px-6">$ {{ product.amount }}</div>
                        
                        </div>
                    </template> 
                    
                


                    <div class="grid grid-cols-12 border-l-2 border-green-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 text-left p-2"><b class="text-green-600">Subtotal </b></div>
                        <div class="col-span-10 text-right py-2 px-6"><b class="text-green-600">$ {{ $store.state.total_amount_order }}</b></div>
                    </div>

                    <div v-if="$store.state.discount_active>0" class="grid grid-cols-12 border-l-2 border-red-600" style="margin: 2px 0 0 0;">
                        <div class="col-span-3 text-left p-2"><b class="text-red-600">Descuento <span v-if="$store.state.percentage_active>0">{{ $store.state.percentage_active+' %' }}</span></b> </div>
                        <div class="col-span-9 text-right py-2 px-6"><b class="text-red-600">$ - {{ $store.state.discount_active }}</b></div>
                    </div>

                    <div v-if="!paycheck" class="grid grid-cols-12 border-l-2 border-gray-400" style="margin: 2px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Propina 10%</div>
                        <div class="col-span-10 text-right py-2 px-6 ">$<input v-model.number="$store.state.tip" class="w-20 focus:ring-transparent" type="text"></div>
                    </div>
                    

                    <div class="grid grid-cols-12 bg-gray-600 text-white" style="margin: 5px 0 0 0;">
                        <div class="col-span-2 text-left p-2">Total </div>
                        <div class="col-span-10 text-right py-2 px-6"><b>$ {{ ($store.state.total_amount_order+$store.state.tip)-$store.state.discount_active }}</b></div>
                    </div>
                    <!--<div class="col-span-6"></div>-->
                    
            </div>
            <div class="p-4 ">
                <button @click = "closeOrderF" class="p-1 rounded-sm bg-orange-200 hover:bg-orange-300 mr-4 p-2  border-t border-b border-l border-r border-orange-700 text-orange-800 float-right ">Confirmar</button>
                    <button @click = "ChangeStateModal" class="p-1 rounded-sm bg-gray-200 hover:bg-gray-300 mr-4 p-2  border-t border-b border-l border-r border-gray-700 text-gray-800 float-right">Cancelar</button>
                    
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

export default {
    emits:['show'],
    setup(props,{emit}){
        const store = useStore();
        closemodalcloseOrder = ()=>{
            emit('show');
            paycheck.value = false;
        }
        

        const closeOrderF = ()=>{
            emit('show');
            store.dispatch("closeOrderAction")
        }

        const patCheckFunction = ()=>{
            paycheck.value =!paycheck.value;
        }
        const payAll = ()=>{
            paycheck.value =!paycheck.value;
        }
        const setShow = ()=>{
            emit('show');
        }

    

        return {closeOrderF,patCheckFunction,paycheck,payAll,setShow}
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
            closemodalcloseOrder();
        }
    },
    
    props:["show"]
}
</script>