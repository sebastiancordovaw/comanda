<template>
    <div style="height:100vh">
            <div v-if="$store.state.tableActivateNumber>0" class="p-4 text-lg text-white bg-red-500">
                <strong class="float-left">MESA {{ $store.state.tableActivateNumber }}</strong>
                <change-table></change-table>
                <div class="clear-both"></div>
            </div>
            <Dropdown align="down" width="100%" :contentClasses="['py-0']">
                <template #trigger>
                    <div class="relative mx-auto text-gray-600">
                        <input placeholder="Buscar Producto..."  v-show="$store.state.tableActivateNumber>0" v-model="searchInput" type="text" @change="clearInput()" @keyup="searchProduct()" @keyup.enter="searchProduct()" name="" id="" class="w-full border-t border-b border-l border-r border-gray-400 focus:ring-transparent focus:border-orange-700">
                    </div>
                </template>

                <template #content>
                    <a v-show = "$store.state.productsSearch.length" v-for="(search, i ) in $store.state.productsSearch" :key="i" href=""  class="text-left hover:bg-orange-300">
                        <div class="grid grid-cols-12 gap-4 px-2 py-2 hover:bg-orange-200" @click.prevent = "addCart1(search)">
                          <div class="col-span-9">{{ search.name }}</div>
                          <div class="col-span-3">{{ search.price }}</div>
                        </div>
                    </a>
                </template>
            </Dropdown>
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

        return { searchProduct, addCart1, clearInput ,searchInput}
    }
    ,components:{
        Dropdown,
        Cart1,
        ChangeTable
    },
    mounted()
    {
       
    }
}

</script>
