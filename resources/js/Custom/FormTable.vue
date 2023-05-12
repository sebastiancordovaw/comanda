<template>
    <div style="height:100vh">
            <div v-if="$store.state.tableActivateNumber>0" class="px-3 py-3 text-lg text-white bg-red-500">
                <strong class="float-left">MESA {{ $store.state.tableActivateNumber }}</strong>
                <change-table></change-table>
                <div class="clear-both"></div>
            </div>
            <Dropdown align="down" width="100%" :contentClasses="['py-0']">
                <template #trigger>
                    <div class="relative mx-auto text-gray-600">
                        <input placeholder="Buscar Producto..."  v-show="$store.state.tableActivateNumber>0" v-model="searchInput" type="text" @change="clearInput()" @keyup="searchProduct()" @keyup.enter="searchProduct()" name="" id="" class="w-full border-t-0 border-b border-l-0 border-r-0 border-gray-400 focus:ring-transparent focus:border-orange-700">
                    </div>
                </template>

                <template #content>
                    <a v-show = "$store.state.productsSearch.length" v-for="(search, i ) in $store.state.productsSearch" :key="i" href=""  class="text-left hover:bg-yellow-300">
                        <div class="grid grid-cols-12 gap-4 px-2 py-2 hover:bg-orange-200" @click.prevent = "addCart1(search)">
                            <div class="col-span-9">{{ search.name }}</div>
                            <div class="col-span-3">{{ search.price }}</div>
                        </div>
                    </a>
                </template>
            </Dropdown>
            <!---BUTON ADD PRODUCT ISMOBILE-->
            <div @click="addProductMobile" class="float-right rounded-full cursor-pointer bg-green-400 p-1 text-white" style="margin-top: -37px; z-index: 88888; position: relative;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <div class="clear-both"></div>
            </div>
            <cart1></cart1>
        </div>
</template>

<script>
import Dropdown from '@/Components/Dropdown.vue';
import Cart1 from '@/Custom/Cart1.vue';
import ChangeTable from '@/Custom/ChangeTable.vue';
import { useStore } from 'vuex';
import { ref, onMounted } from 'vue';
import{onBeforeMount} from 'vue';

import EventBus from '../EventBus.js';
const searchInput = ref('');
export default {
    data(){
        return{
        }
    },
    methods:{

    },setup()
    {
        const store = useStore();
        const searchProduct = () =>{
            store.dispatch('getProducts',searchInput.value);
        }

        const clearInput = () =>{
            searchInput.value="";
        }

        const addCart1 = (product,input) =>{
            store.dispatch('addCart1Action',product);

        }

        onMounted(() => {
            store.dispatch('getOrder');
        })

        onBeforeMount(() => {
            if(localStorage.getItem("cart1")!='[object Object]' && localStorage.getItem("cart1")!=null)
            {
                store.state.cart1 = JSON.parse(localStorage.getItem("cart1"));
            }

            if(localStorage.getItem("table")!=null)
            {
                store.state.tableActivate = localStorage.getItem("table");
            }

            if(localStorage.getItem("tableNumber")!=null)
            {
                store.state.tableActivateNumber = localStorage.getItem("tableNumber");
            }
        })
        const addProductMobile = () =>{
            EventBus.emit("addProductMobile")
        }


        return { searchProduct, addCart1, clearInput ,searchInput, addProductMobile}
    }
    ,components:{
        Dropdown,
        Cart1,
        ChangeTable,
    },
    mounted()
    {

    }
}

</script>
