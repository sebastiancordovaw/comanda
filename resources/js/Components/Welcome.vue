
<template>
    <div class="grid grid-cols-4 gap-4">
        
        <div  class="col-span-2 p-5" >
            <button v-show = "$store.state.tables.length"  v-for="(table, i ) in $store.state.tables" :key="i"  :class="table.status == 1 ?'open':''"  :id="'table_'+table.id"   @click = "selectTable(table)" class="w-24 h-24 float-left text-center bg-green-500 hover:bg-green-400 rounded-md mr-4 text-3xl text-white border-2 border-green-700 tables">{{table.number}}</button>
        </div>
        <div class="col-span-2">
            <change-table></change-table>
            <form-table></form-table>
        </div>
    </div>
</template>

<script>
import FormTable from '@/Custom/FormTable.vue';
import ChangeTable from '@/Custom/ChangeTable.vue';
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
                document.getElementsByClassName("tables")[i].classList.remove("selectTable")
            }
            
            document.getElementById("table_"+table.id).classList.add("selectTable");
            
            store.dispatch('setActive', table);
            store.dispatch('getOrder');

            store.state.discount          =0;
            store.state.porcentage        =0;
            store.state.showDiscount = false;

        }
        onUpdated(()=>{

            if(document.getElementById("table_"+localStorage.getItem("table"))!=null)
            {
                document.getElementById("table_"+localStorage.getItem("table")).classList.add("selectTable");
            }
            
        })
        

        return {selectTable}
    }
    ,
    components:{
        FormTable,
        ChangeTable
    },
    methods:{
        
    },
    created(){
      
        
    }
}
</script>
<style>
.selectTable{
    -webkit-box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
-moz-box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
}
.selectTable:hover{
    -webkit-box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
-moz-box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
box-shadow: 0px 0px 23px 8px rgba(245,202,10,1);
}
.open{
background-color: #e74c3c;
border-color: #c0392b;
}

.open:hover{
background-color: #ff7979;
}
</style>