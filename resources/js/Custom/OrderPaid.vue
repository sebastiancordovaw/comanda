<template>
    <modal  :show = "show" @close="ChangeStateModal">
        <div class="p-4 bg-gray-600 text-white">
            Mesa {{ $store.state.tableActivateNumber }}
            <div class="clear-both"></div>
        </div>

        <div class="p-4 bg-gray-400 text-white"><b>ADICIONES</b></div>

        <div v-show="$store.state.products.length">
            <template v-for="(product) in $store.state.products" :key  = "product.id">
                <div v-if="product.date_pay!=null" class="grid grid-cols-12 bg-gray-10 border-gray-800 mt-1 border-l-4 product"  :id="product.id" :class ="product.status?'':'delete'" :paid = "(product.date_pay!=null)?true:false" :is_percentage = "(product.is_percentage)?true:false" :date_pay="product.date_pay">
                    <div class="col-span-2 p-2">{{ product.count }}</div>
                    <div class="p-2 col-span-4"><b>{{ product.name }}</b><p><small class="text-sm">{{ product.note }}</small></p></div>
                    <div class="col-span-2 text-center text-red-600 p-2 " v-if="product.percentage==0">
                        <svg v-if="product.status>0 && product.date_pay==null" xmlns="http://www.w3.org/2000/svg" @click="addDiscountProduct(product)" fill="none" style="margin:0 auto; position: relative; cursor: pointer;" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" />
                        </svg>
                    </div>

                    <div class="col-span-2  text-red-600 p-2 " v-if="product.percentage>0"><b>{{ product.percentage }} %</b>
                            <svg v-if="product.status>0 && product.date_pay==null" xmlns="http://www.w3.org/2000/svg" @click="delDiscountProduct(product.id)" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 float-right">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    <div class=" p-2 col-span-3 text-right"><span :class="(product.percentage)?'line-through text-gray-600':''"> $ {{ product.amount }}</span> <span class="text-red-600 font-bold" v-if="product.percentage">$ {{ product.amount - (product.amount * product.percentage / 100)}}</span></div>
                    <div class="col-span-1 p-2">
                        <svg v-if="product.status>0 && product.date_pay==null" :id="'trash_'+product.id"  @click="delProduct(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-gray-600 hover:text-gray-900">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>

                        <svg v-if="product.status>0 && product.date_pay!=null" :id="'trash_'+product.id"  @click="delProductPaid(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-5 h-5 text-center text-gray-600 hover:text-gray-900">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </div>
                </div>
            </template>

            <div v-if="$store.state.total_amount_order_paid>0"  class="grid grid-cols-12 bg-gray-100 border-green-500 mt-1 border-l-4">
                <div class="col-span-3 p-2 "><b class="text-green-500">Pagado</b></div>
                <div class="col-span-5 p-2"></div>
                <div class="col-span-3 text-right p-2"><b class="text-green-500">$ {{ $store.state.total_amount_order_paid + $store.state.tip}}</b></div>
            </div>
        </div>
        <div v-show="$store.state.products.length>0" class="mt-2 p-4">
            <button @click = "closeModal" class="rounded-sm bg-cyan-400 hover:bg-cyan-500 p-2 border-t border-b border-l border-r float-right text-white ">Cerrar</button>
            <div class="clear-both"></div>
        </div>
    </modal>
</template>

<script>
import { useStore } from 'vuex';
import Modal from '@/Components/Modal.vue';
import Swal from 'sweetalert2';
let closeModal = null;
export default {

    data(){
        return{

        }
    },
    emits:['show'],
    setup(props,{emit}) {
        const store = useStore();
        closeModal = ()=>{
            emit('showOrderPaid');
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
                            store.dispatch('deleteProductOrderPaid', product);
                            emit('showOrderPaid');

                        }
            })
        }

        return {delProductPaid,closeModal}
    },
    methods:{
        ChangeStateModal()
        {
            closeModal();
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
        Modal
    },
    props:['show']
}
</script>

<style>


.paid{
    background-color:#7bed9f;
}

.paid:hover{
    background-color:#7bed9f;
}
</style>
