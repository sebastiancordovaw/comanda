
<template>
    <div class="grid grid-cols-4 gap-4">
        
        <div  class="col-span-2">
            <button v-show = "$store.state.tables.length" v-for="(table, i ) in $store.state.tables" :key="i"  @click = "selectTable(table)" class="w-24 h-24 float-left text-center bg-green-500 hover:bg-green-400 rounded-md mr-4 text-3xl text-white border-2 border-green-700 focus:bg-yellow-500 focus:border-yellow-600">{{table.number}}</button>
        </div>
        <div class="col-span-2">
            <form-table></form-table>
        </div>
    </div>
</template>

<script>
import FormTable from '@/Custom/FormTable.vue';
import { onMounted } from 'vue'
import { useStore } from 'vuex';
export default {
    data(){
        return{

        }
    },
    setup()
    {
        const store = useStore();
        onMounted(()=>{
            store.dispatch('getTables')
        });

        const selectTable = table =>{
            
            store.dispatch('setActive', table);
            store.dispatch('getOrder');
        }
        return {selectTable}
    }
    ,
    components:{
        FormTable
    },
    methods:{
        
    },
    created(){
        
    }
}
</script>