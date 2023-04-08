<template>
    <div style="height:100vh">
            <div v-if="$store.state.tableActivateNumber>0" class="bg-red-500 text-white text-lg p-4">
                <strong>MESA {{ $store.state.tableActivateNumber }}</strong>
            </div>
            <Dropdown align="down" width="100%" :contentClasses="['py-0']">
                <template #trigger>
                    <div class="relative mx-auto text-gray-600">
                        <input  v-show="$store.state.tableActivate>0" v-model="searchInput" type="text" @keyup="searchProduct(searchInput)" name="" id="" class="focus:ring-transparent w-full border-t border-b border-l border-r border-gray-400 focus:border-orange-700">
                    </div>
                </template>

                <template #content>
                    <a v-show = "$store.state.productsSearch.length" v-for="(search, i ) in $store.state.productsSearch" :key="i" href=""  class="hover:bg-orange-300 text-left">
                        <div class="hover:bg-orange-200 py-2 px-2 grid grid-cols-12 gap-4" @click.prevent = "addCart1(search)">
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
import { useStore } from 'vuex';
import { onMounted } from 'vue';
import{onBeforeMount} from 'vue';

export default {
    data(){
        return{
            searchInput: ""
        }
    },
    methods:{

    },setup()
    {
        const store = useStore();
        const searchProduct = searchInput =>{
            store.dispatch('getProducts',searchInput);
        }

        const addCart1 = product =>{
            store.dispatch('addCart1Action',product);
        }

        onMounted(() => {
            store.dispatch('getOrder');
        })

        onBeforeMount(() => {
            if(localStorage.getItem("cart1")!=null)
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

        return { searchProduct, addCart1 }
    }
    ,components:{
        Dropdown,
        Cart1
    }
}
</script>
