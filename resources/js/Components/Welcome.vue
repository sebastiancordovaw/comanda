
<template>
    <div class="grid grid-cols-4 gap-4">
        
        <div  class="col-span-2">
            <button v-show = "$store.state.tables.length" v-for="(table, i ) in $store.state.tables" :key="i"  :id="'table_'+table.id"   @click = "selectTable(table)" class="w-24 h-24 float-left text-center bg-green-500 hover:bg-green-400 rounded-md mr-4 text-3xl text-white border-2 border-green-700 focus:bg-yellow-500 focus:border-yellow-600 tables">{{table.number}}</button>
        </div>
        <div class="col-span-2">
            <form-table></form-table>
        </div>
    </div>
</template>

<script>
import FormTable from '@/Custom/FormTable.vue';
import { onMounted, onUpdated } from 'vue'
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
            
            for(let i = 0; i<document.getElementsByClassName("tables").length; i++){
                document.getElementsByClassName("tables")[i].classList.remove("bg-yellow-500","border-yellow-600")
                document.getElementsByClassName("tables")[i].classList.add("bg-green-500", "hover:bg-green-400")
            }

            document.getElementById("table_"+table.id).classList.add("bg-yellow-500","border-yellow-600");
            document.getElementById("table_"+table.id).classList.remove("bg-green-500", "hover:bg-green-400");
            
            store.dispatch('setActive', table);
            store.dispatch('getOrder');
        }
        onUpdated(()=>{

            if(document.getElementById("table_"+localStorage.getItem("table"))!=null)
            {
                document.getElementById("table_"+localStorage.getItem("table")).classList.add("bg-yellow-500","border-yellow-600");
                document.getElementById("table_"+localStorage.getItem("table")).classList.remove("bg-green-500", "hover:bg-green-400");
            }
            
        })
        

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