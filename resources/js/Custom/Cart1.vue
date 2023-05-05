<template>

    <div v-if ="$store.state.cart1[$store.state.tableActivate]" class="grid grid-cols-12 mt-1 bg-yellow-200 p-1">
        <template  v-for="(product,i)  in $store.state.cart1[$store.state.tableActivate]" :key="i">
            <div><button @click = "decrease(product)" class="bg-green-400 px-3 py-1 rounded-l-sm w-full text-white font-bold">-</button></div>
            <div  class="col-span-1"><input :value="product.count" class="text-center w-full shadow-none outline-0 focus:ring-transparent border-0 focus:border-0  p-1" type="text"/></div>
            <div><button @click = "inclement(product)" class="bg-green-400 px-3 py-1 rounded-r-sm  w-full text-white font-bold">+</button></div>
            <div class="col-span-5 pt-1 px-2"><p class="text-lg">{{ product.name }} </p><p><small class="text-sm">{{ product.note }}</small></p> </div>
            <div class="col-span-2 gap-2">
                <div class="grid grid-cols-12">
                <div class="col-span-2 pt-1">$</div>
                <div class="col-span-10 pt-1"><input v-model.number="$store.state.cart1[$store.state.tableActivate][product.id].total_amount" class="text-center shadow-none outline-0 focus:ring-transparent border-0 focus:border-0  text-xs lg:text-sm p-1 w-10 lg:w-full" type="text"/></div>
            </div>

            </div>

            <div class="pt-2 items-center"><svg @click="commentProduct(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-center text-orange-400 hover:text-orange-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
            </svg>
            </div>
            <div class="pt-2 items-center"><svg @click="del(product)" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto; position: relative; cursor: pointer;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-center text-orange-400 hover:text-orange-600">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
            </div>
        </template>
    </div>
    <div v-show="total>0" class="p-2 bg-gray-600 text-white mt-1">Total a confirmar ${{ total }}</div>
    <div v-show="total>0" class="mt-2">
        <button @click = "cancelCart1" class="rounded-sm bg-gray-400 hover:bg-gray-500 mr-4 p-2  text-white ">Cancelar</button>
        <button @click = "confirmCart1" :disabled="$store.state.loadData" class="rounded-sm bg-green-400 hover:bg-green-500 mr-4 p-2 text-white ">Confirmar</button>
    </div>

    <detail-order></detail-order>



</template>
<script>
import { useStore } from 'vuex';
import DetailOrder from '@/Custom/DetailOrder.vue';
import Swal from 'sweetalert2';
export default {
    data()
    {
        return{

        }
    }
    ,setup(){
        const store = useStore();

        const inclement = product => {

            store.state.cart1[store.state.tableActivate][product.id].count++;
            store.state.cart1[store.state.tableActivate][product.id].total_amount = store.state.cart1[store.state.tableActivate][product.id].count * store.state.cart1[store.state.tableActivate][product.id].price
            localStorage.setItem("cart1",  JSON.stringify(store.state.cart1));
        }
        const decrease = product => {
            if(store.state.cart1[store.state.tableActivate][product.id].count>=2)
            {
                store.state.cart1[store.state.tableActivate][product.id].count--;
                store.state.cart1[store.state.tableActivate][product.id].total_amount = store.state.cart1[store.state.tableActivate][product.id].count * store.state.cart1[store.state.tableActivate][product.id].price

                localStorage.setItem("cart1",  JSON.stringify(store.state.cart1));
            }

        }

        const del = product => {
            delete store.state.cart1[store.state.tableActivate][product.id];
            localStorage.setItem("cart1",  JSON.stringify(store.state.cart1));
        }
        const cancelCart1 = any => {
            delete store.state.cart1[store.state.tableActivate];
            localStorage.setItem("cart1",  JSON.stringify(store.state.cart1));
        }

        const confirmCart1 = any => {
            store.state.loadData = true;
            store.dispatch('confirmCart1Action');
        }

        const commentProduct = product => {

            Swal.fire({
                title: 'Agregar un comentario',
                input: 'text',
                inputValue: product.note,
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#27ae60',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                preConfirm: (comment) => {
                    store.dispatch('commentAction',{product,comment});
                }
            })


            /*******/

        }

        return {decrease,inclement,del,confirmCart1,cancelCart1,commentProduct}
    }
    ,methods:{

    },
    computed: {
        total () {
            const store = useStore();
            let total_cart1  = 0;
            if(store.state.cart1!=null)
            {
                if(store.state.cart1[store.state.tableActivate]!=undefined)
                {

                    let keys = Object.keys(store.state.cart1[store.state.tableActivate]);
                    for(let i = 0; i<keys.length; i++ ){
                        total_cart1 = total_cart1 + parseInt(store.state.cart1[store.state.tableActivate][keys[i]].total_amount);
                    }
                    return total_cart1;
                }

            }


        }
  },
  components:{
    DetailOrder
  }
}
</script>
