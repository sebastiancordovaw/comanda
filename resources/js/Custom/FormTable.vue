<template>
    <div class="grid grid-cols-12 gap-4" style="height:100vh">

        <div class="col-span-2">
            <button class="w-full p-1 rounded-md bg-orange-500 mr-4 hover:bg-orange-400  border-t border-b border-l border-r border-orange-700 text-white text-2xl">+</button>
        </div>

        <div class="col-span-10">
    
            <Dropdown align="down" width="100%" :contentClasses="['py-0']">
                <template #trigger>
                    <div class="relative mx-auto text-gray-600">
                        <input v-model="searchInput" type="text" @keyup="searchProduct(searchInput)" name="" id="" class="focus:ring-transparent w-full border-t border-b border-l border-r border-gray-400 focus:border-orange-700">
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
            
            
            
        </div>

    </div>
    
    
</template>

<script>
import Dropdown from '@/Components/Dropdown.vue';
import { useStore } from 'vuex';

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
        return { searchProduct, addCart1 }
    }
    ,components:{
        Dropdown
    }
}
</script>

<style>
#app {
  text-align: center;
}
</style>